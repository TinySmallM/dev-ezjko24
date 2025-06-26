<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Order;
use common\models\OrderItem;
use common\models\Helpers;
use common\models\Coupon;
use common\models\OrderCoupon;
use common\models\PlatformCode;
use common\models\TelegramApi;
use common\models\Cart;
use frontend\controllers\FingerprintController;




use Lexty\Robokassa\Client as RobokassaClient;
use Lexty\Robokassa\Payment as RobokassaPayment;
use Lexty\Robokassa\Auth as RobokassaAuth;


class CartController extends Controller {
    
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
					'process' => ['get','post'],
					'process-cp' => ['post'],
					'success' => ['get'],
					'fail' => ['get'],
                    'api' => ['post']
                ],
            ],
        ];
    }
    public function beforeAction($action){
    	Helpers::currentRegion();
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
    
    //Страница корзины
    public function actionIndex(){
    	
    	session_start();

		if( Yii::$app->request->get('cart_hash') ){
			$this->loadCartByHash( Yii::$app->request->get('cart_hash') );
		}
    	
    	$ids = [];
    	//$_SESSION['cart'] = [];
    	if( isset($_SESSION['cart']) ) $ids = $_SESSION['cart'];
    	
    	$data['items'] = Product::find()->where(['id'=>array_keys($ids)])->asArray()->all();
    	
    	foreach($data['items'] as $i=>$d){
    		$data['items'][$i]['cartCount'] = $ids[$d['id']];
    	}

		$o_status = [];
		if( Yii::$app->request->get('hash') ){
			$o_status = Order::find()
				->where(['hash'=>Yii::$app->request->get('hash')])
				->with(['dolyame'])
				->asArray()->one();
		}
    	
    	$this->layout = 'main';
		if( Yii::$app->request->get('dolyame') ) return $this->render('index_new',['data'=>$data,'o_status'=>$o_status]);

		return $this->render('index_new',['data'=>$data,'o_status'=>$o_status]);
    	
		//return $this->render('index',['data'=>$data]);
    }

	public function actionFail(){
		$this->layout = 'main';
    	return $this->render('result',['state'=>'error','order'=>[]]);
	}

	public function actionSuccess(){
		$this->layout = 'main';

		session_start();
		$order = [];
		if( isset($_SESSION['last_order_id']) && $_SESSION['last_order_id'] ) {
			$order = Order::find()->where(['hash'=>$_SESSION['last_order_id']])->with(['items'])->asArray()->one();
			$_SESSION['last_order_id'] = null;
		}
		$_SESSION['cart'] = [];
		$_SESSION['coupon'] = [];
		$_SESSION['cart_id'] = null;
    	return $this->render('result',['state'=>'success','order'=>$order]);
	}
	
	public function actionProcess(){
		//$payment = new RobokassaPayment(
		//	new RobokassaAuth(Yii::$app->params['robokassa']['login'], Yii::$app->params['robokassa']['pass1'], Yii::$app->params['robokassa']['pass2'], true)
		//);

		$out_summ = $_POST["OutSum"];
		$inv_id = $_POST["InvId"];
		$crc = strtoupper($_REQUEST["SignatureValue"]);

		$mrh_pass2 = Yii::$app->params['robokassa']['pass2'];

		$my_crc = strtoupper( md5("$out_summ:$inv_id:$mrh_pass2") );
			
		if ($my_crc != $crc) {
			Yii::$app->response->statusCode = 400;
			return 'BAD SIGN';
		}
		
		$order = Order::find()->where(['id'=>$_POST['InvId']])->one();
		if(!$order) return 'Order not found';

		$oi = OrderItem::find()->where(['order_id'=>$order->id])->asArray()->all();

		//status already is 3
		if($order->status == 3){
			echo 'OK'.$inv_id;
			return;
		}

		$order->status = 2;
		$order->extPayDateCompleted = date('Y-m-d H:i:s');
		$order->save();
		
		$order->status = 3;
		$order->save();

		return 'OK'.$inv_id;
	}

	public function actionProcessCp(){

		session_start();

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		//$payment = new RobokassaPayment(
		//	new RobokassaAuth(Yii::$app->params['robokassa']['login'], Yii::$app->params['robokassa']['pass1'], Yii::$app->params['robokassa']['pass2'], true)
		//);


		$out_summ = $_POST["PaymentAmount"];
		$inv_id = $_POST["InvoiceId"];

		//$crc = strtoupper($_REQUEST["SignatureValue"]);

		//$mrh_pass2 = Yii::$app->params['robokassa']['pass2'];

		//$my_crc = strtoupper( md5("$out_summ:$inv_id:$mrh_pass2") );
			
		/*if ($my_crc != $crc) {
			Yii::$app->response->statusCode = 400;
			return 'BAD SIGN';
		}
		*/
		
		$order = Order::find()->where(['id'=>$inv_id])->one();
		if(!$order) return 'Order not found';

		$oi = OrderItem::find()->where(['order_id'=>$order->id])->asArray()->all();

		//status already is 3
		if($order->status == 3){
			return ['code'=>0];
		}

		$order->status = 2;
		$order->extPayDateCompleted = date('Y-m-d H:i:s');
		$order->save();
		
		$order->status = 3;
		$order->save();

		//сообщение о покупке в телегу
		/*
		$msg = '================='.PHP_EOL.'Заказ <b>#'.$inv_id.'</b> на сумму <b>'.$order->amount.' руб</b>'.PHP_EOL.'================='.PHP_EOL;
		$msg .= '|  Email: '.$order->email.PHP_EOL;
		$msg .= '|  Телефон: '.$order->phone.PHP_EOL;
		if($order->platformCode) $msg .= '|  Код активации: '.$order->platformCode.PHP_EOL;

		$msg .= '|'.PHP_EOL;

		$msg .= '|  Корзина:'.PHP_EOL;

		foreach($oi as $i=>$d){
			$msg .= '|  – x'.$d['quantity'].' '.$d['name']. ' за '.($d['coupon_priceSum']?$d['coupon_priceSum'].' руб/шт (промокод)':$d['priceSum'].' руб/шт').PHP_EOL;
		}

		$msg .= '|'.PHP_EOL;

		$msg .= '|  <a href="https://christmedschool.com/master/order/'.$order->id.'">Открыть в админке</a>'.PHP_EOL;

		$msg  .= '=================';

		$TelegramApi = new TelegramApi();
        $TelegramApi->user_id = 186982550; //186982550;
        $TelegramApi->text = $msg;
        $TelegramApi->sendMessage();

		$TelegramApi = new TelegramApi();
        $TelegramApi->user_id = 270089019; //186982550;
        $TelegramApi->text = $msg;
        $TelegramApi->sendMessage();
		*/



		

		return ['code'=>0];
	}
	
    
	//API
	public function actionApi(){
		session_start();

		
		//$_SESSION['cart'] = [];
		//$_SESSION['coupon'] = [];
		if( empty($_SESSION['cart']) ) $_SESSION['cart'] = [];
		if( empty($_SESSION['coupon']) ) $_SESSION['coupon'] = [];
		
		$reqBody = Yii::$app->request->bodyParams;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$data = [];

		if (Yii::$app->request->get('type') == 'getOrderByHash'){
			$o = Order::find()->where(['hash'=>$reqBody['hash']])->with(['items'])->asArray()->one();
			$ya_cart = [];
			if($o){
				foreach($o['items'] as $i=>$d){
					$ya_cart[] = [
						'id'=>$d['product_id'],
						'name'=>$d['name'],
						'price'=>$d['coupon_priceSum']?$d['coupon_priceSum']:$d['priceSum'],
						'position'=>$i+1,
						'variant' => $d['priceName'],
						'brand'=>'ChristMedSchool',
					];
				}
			}

			return ['id'=>$o['id'],'status'=>$o['status'],'platformCode'=>$o['platformCode'],'ya_cart'=>$ya_cart];
		}
		
		if (Yii::$app->request->get('type') == 'update'){
			
			if( !array_key_exists('count',$reqBody) || $reqBody['count'] < 0 || $reqBody['count'] > 1000) return ['error'=>'Некорректное количество'];
			if( !array_key_exists('id',$reqBody) || !Product::findOne($reqBody['id']) ) return ['error'=>'Некорректный ID продукта'];
			$pr = Product::findOne($reqBody['id']);
			$pr_cat = '';
			
			if(!$pr) return ['error'=>'Product not found'];

			//$pr_cat_1 = Page::find()->where()
			
			
			if( !empty($reqBody['priceType']) && !in_array($reqBody['priceType'],[1,2]) ) return ['error'=>'priceType not ok'];
				
			
			//Добавляем элемент (если нет)
			if( empty($_SESSION['cart'][$reqBody['id']]) ){
				
				if( empty($reqBody['priceType']) ) return ['error'=>'priceType not specified'];
				
				$_SESSION['cart'][$reqBody['id']] = [
					'h1'=>$pr->title,
					'count'=>$reqBody['count'], 
					'nds'=>$pr->nds,
					'image'=>$pr->image,
				];	
			}
			
			if( !empty($reqBody['priceType']) ){
				
				$_SESSION['cart'][$reqBody['id']]['priceType'] = $reqBody['priceType'];

				$_SESSION['cart'][$reqBody['id']]['coupon_priceSum'] = null;

				
				if($reqBody['priceType'] == 1){
					$_SESSION['cart'][$reqBody['id']]['priceSum'] = $pr->price1_sum;
					$_SESSION['cart'][$reqBody['id']]['priceName'] = $pr->price1_name;

					$_SESSION['cart'][$reqBody['id']]['artikul'] = $pr->artikul1;
				}
				if($reqBody['priceType'] == 2){
					$_SESSION['cart'][$reqBody['id']]['priceSum'] = $pr->price2_sum;
					$_SESSION['cart'][$reqBody['id']]['priceName'] = $pr->price2_name;

					$_SESSION['cart'][$reqBody['id']]['artikul'] = $pr->artikul2;
				}				
			}
			
			//Устаналиваем значение или складываем с текущим
			else if( Yii::$app->request->get('set') == 1){
				$_SESSION['cart'][$reqBody['id']]['count'] = $reqBody['count'];
			}
			//else $_SESSION['cart'][$reqBody['id']]['count'] += $reqBody['count'];
						
			//Удаляем, если количество = 0
			if($_SESSION['cart'][$reqBody['id']]['count'] == 0) unset($_SESSION['cart'][$reqBody['id']]);

			$this->calcCartWithCoupon();
			
			FingerprintController::addAction('cart_update');
			
			return [
				'success'=>true,
				'items'=>$_SESSION['cart'],
				'count'=>count($_SESSION['cart']),
				'coupon'=>$this->returnCouponFields(),
				'item'=>[
					'id'=>$pr->id,
					'h1'=>$pr->h1,
					'count' => isset($_SESSION['cart'][$reqBody['id']])?$_SESSION['cart'][$reqBody['id']]['count']:0,
					'priceSum' => isset($_SESSION['cart'][$reqBody['id']])?$_SESSION['cart'][$reqBody['id']]['priceSum']:0,
					'priceName' => isset($_SESSION['cart'][$reqBody['id']])?$_SESSION['cart'][$reqBody['id']]['priceName']:'',
				]
			];
		}
		
		if (Yii::$app->request->get('type') == 'get'){
			return ['items'=>$_SESSION['cart'],'coupon'=>$this->returnCouponFields()];
		}
		
		if (Yii::$app->request->get('type') == 'clear'){

			if($reqBody['type'] == 'promo'){
				$this->removeCoupon();
				FingerprintController::addAction('cart_clear_promo');
			}
			else {
				$this->removeCoupon();
				$_SESSION['cart'] = [];
				$_SESSION['cart_id'] = null;
				FingerprintController::addAction('cart_clear_all');
			}
			
			
			return ['success'=>true,'items'=>$_SESSION['cart'],'coupon'=>$this->returnCouponFields()];
		}
		
		if (Yii::$app->request->get('type') == 'process'){
			
			if(!$_SESSION['cart']) return ['error'=>'Cart is empty'];

			$yaItems = [];
			
			//Считаем итоговую сумму
			$sum = 0;
			$sumWithCoupon = 0;
			foreach($_SESSION['cart'] as $p){
				$sum += round($p['count']*$p['priceSum'], 2);
				$sumWithCoupon += round($p['count']*$p['coupon_priceSum']?$p['coupon_priceSum']:$p['priceSum'], 2);
			}
			
			//Создаем заказ в БД
			$o = new Order;
			$o->name = strip_tags( $reqBody['name'] );
			$o->email = strip_tags( trim($reqBody['email']) );
			$o->phone = strip_tags( trim($reqBody['phone']) );
			$o->comment = '';//strip_tags($reqBody['comment']);
			$o->payMethod = 1;
			$o->orgType = 1;//$reqBody['orgType'];
			$o->delivery = 1;//$reqBody['delivery'];
			$o->amount = round($sumWithCoupon, 2);
			$o->status = 1;
			$o->fingerprintId = isset($_SESSION['fingerprint']['id'])?$_SESSION['fingerprint']['id']:null;
			$o->hash = Helpers::randStr(32);
			if(!$o->save()){
				return $o->getErrors();
			}
			$o->id = $o->id;

			if($_SESSION['coupon']){
				$oc = new OrderCoupon;
				$oc->orderId = $o->id;
				$oc->data = json_encode($_SESSION['coupon']);
				$oc->orderSumBefore = $sum;
				$oc->orderSumAfter = $sumWithCoupon;
				$oc->couponId = $_SESSION['coupon']['id'];
				$oc->text = $_SESSION['coupon']['text'];
				if(!$oc->save()){
					return $oc->getErrors();
				}
			}
			
			$nameByOrder = '';
			//Добавляем позиции в заказ
			foreach($_SESSION['cart'] as $p_i=>$p_d){
				$oi = new OrderItem;
				
				$oi->order_id = $o->id;
				$oi->product_id = $p_i;
				$oi->name = $p_d['h1'];
				$oi->priceSum = $p_d['priceSum'];
				$oi->coupon_priceSum = $p_d['coupon_priceSum'];
				$oi->quantity = $p_d['count'];
				$oi->cost = round($p_d['count']*$p_d['coupon_priceSum']?$p_d['coupon_priceSum']:$p_d['priceSum'], 2);
				$oi->nds = null;//$p_d['nds'];
				$oi->priceName = $p_d['priceName'];
				$oi->priceType = $p_d['priceType'];
				$oi->artikul = isset($p_d['artikul'])?$p_d['artikul']:null;

				$nameByOrder .= $p_d['h1'] .' [' . $p_d['priceName'] .']; ';

				$yaItems[] = [
					'id'=>$oi->product_id,
					'name'=>$oi->name,
					'price'=>$p_d['h1'],
					'variant'=>$oi->priceSum,
					'quantity'=>$oi->quantity
				];
				
				if(!$oi->save()){
					return $oi->getErrors();
				}
			}

			if( strlen($nameByOrder) > 100 ) $nameByOrder = 'Оплата заказа #'.$o->id.' в магазине '.Yii::$app->params['projectName'];
			
						
			//$payment = new RobokassaPayment(new RobokassaAuth(Yii::$app->params['robokassa']['login'], Yii::$app->params['robokassa']['pass1'], Yii::$app->params['robokassa']['pass2'], false));
			
			

			/*
			$payment
				->setEmail($o->email)
			    ->setId($o->id)
			    ->setSum($o->amount)
				//->setTest(1) //set test
			    ->setCulture(RobokassaClient::CULTURE_RU)
			    ->setDescription($nameByOrder);
			    
			$o->extPayUrl = $payment->getPaymentUrl();
			$o->save();
			
			
			$_SESSION['cart'] = [];
			$_SESSION['coupon'] = [];
			*/
			
			//exec('/opt/php72/bin/php '.Yii::$app->basePath.'/../yii mail/order '.$o->id.' new > /dev/null 2>/dev/null &');

			$o->save();
			Order::sendTgMessage($o->id);

			$_SESSION['coupon'] = null;
			$_SESSION['cart'] = null;
			
			FingerprintController::addAction('cart_process');
			
			return ['success'=>true,'url'=>$o->extPayUrl,'id'=>$o->id,'items'=>$yaItems];

			
		}

		if (Yii::$app->request->get('type') == 'process_cp'){
			
			if( empty($_SESSION['cart']) ) return ['error'=>'Cart is empty'];

			if($_SESSION['coupon'] && isset($_SESSION['coupon']['emailBind']) && $_SESSION['coupon']['emailBind']){
				if( $_SESSION['coupon']['emailBind'] != strtolower($reqBody['email']) ) return ['success'=>false,'msg'=>'Промокод в этом заказе закреплен за другим Email.'];
			}

			if($_SESSION['coupon'] && !(Coupon::canUsageByCount( $_SESSION['coupon']['text'] )) ){
				return ['success'=>false,'msg'=>'Превышено количество использований для этого промокода.'];
			}

			$yaItems = [];
			$receipt = [];
			
			//Считаем итоговую сумму
			$sum = 0;
			$sumWithCoupon = 0;
			foreach($_SESSION['cart'] as $p){
				$sum += round($p['count']*$p['priceSum'], 2);
				$sumWithCoupon += round($p['count']*$p['coupon_priceSum']?$p['coupon_priceSum']:$p['priceSum'], 2);
			}
			
			//Создаем заказ в БД
			$o = new Order;
			$o->name = strip_tags( $reqBody['name'] );
			$o->email = strip_tags( trim($reqBody['email']) );
			$o->phone = strip_tags( trim($reqBody['phone']) );
			$o->comment = '';//strip_tags($reqBody['comment']);
			$o->payMethod = 1;
			$o->orgType = 1;//$reqBody['orgType'];
			$o->delivery = 1;//$reqBody['delivery'];
			$o->amount = round($sumWithCoupon, 2);
			$o->status = 1;
			$o->fingerprintId = isset($_SESSION['fingerprint']['id'])?$_SESSION['fingerprint']['id']:null;
			$o->hash = Helpers::randStr(32);

			if( isset($_SESSION['cart_id']) ) $o->cartId = $_SESSION['cart_id'];

			if(!$o->save()){
				return $o->getErrors();
			}
			$o->id = $o->id;

			if($o->email){
				//exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii mail/subscribe '.$o->id.' > /dev/null 2>/dev/null &'); // подписываем на рассылку
			}

			if($_SESSION['coupon']){
				$oc = new OrderCoupon;
				$oc->orderId = $o->id;
				$oc->data = json_encode($_SESSION['coupon']);
				$oc->orderSumBefore = $sum;
				$oc->orderSumAfter = $sumWithCoupon;
				$oc->couponId = $_SESSION['coupon']['id'];
				$oc->text = $_SESSION['coupon']['text'];
				if(!$oc->save()){
					return $oc->getErrors();
				}

				Coupon::plusUsage( $_SESSION['coupon']['text'] );
				
			}
			
			$nameByOrder = '';
			//Добавляем позиции в заказ
			foreach($_SESSION['cart'] as $p_i=>$p_d){
				$oi = new OrderItem;
				
				$oi->order_id = $o->id;
				$oi->product_id = $p_i;
				$oi->name = $p_d['h1'];
				$oi->priceSum = $p_d['priceSum'];
				$oi->coupon_priceSum = $p_d['coupon_priceSum'];
				$oi->quantity = $p_d['count'];
				$oi->cost = round($p_d['count']*$p_d['coupon_priceSum']?$p_d['coupon_priceSum']:$p_d['priceSum'], 2);
				$oi->nds = null;//$p_d['nds'];
				$oi->priceName = $p_d['priceName'];
				$oi->priceType = $p_d['priceType'];
				$oi->artikul = isset($p_d['artikul'])?$p_d['artikul']:null;

				$nameByOrder .= $p_d['h1'] .' [' . $p_d['priceName'] .']; ';

				$receipt[] = 
				[
                    "label" => $oi->name, //наименование товара
                    "price" => floatval($oi->priceSum), //цена
                    "quantity" => $oi->quantity, //количество
                    "amount" => $oi->cost, //сумма
                    "vat" => 0, //ставка НДС
					"method" => 1,
					"object" => 4,
                ];

				$yaItems[] = [
					'id'=>$oi->product_id,
					'name'=>$oi->name,
					'price'=>$p_d['h1'],
					'variant'=>$oi->priceSum,
					'quantity'=>$oi->quantity,
					"measurementUnit"=> "шт"
				];
				
				if(!$oi->save()){
					return $oi->getErrors();
				}
			}

			if( strlen($nameByOrder) > 100 ) $nameByOrder = 'Оплата заказа #'.$o->id.' в магазине '.Yii::$app->params['projectName'];
			
			//exec('/opt/php72/bin/php '.Yii::$app->basePath.'/../yii mail/order '.$o->id.' new > /dev/null 2>/dev/null &');

			//Cancel previous orders
			if($o->email && $o->phone){
				$prev = Order::find()
					->where(['email'=>$o->email,'phone'=>$o->phone,'status'=>1])
					->andWhere(['<','id',$o->id])
					->all();
				foreach($prev as $i=>$d){
					$d->status = 4;
					$d->save();
				}	
			}
			
			FingerprintController::addAction('cart_process');

			$_SESSION['last_order_id'] = $o->hash;
			
			return ['success'=>true,'id'=>$o->id,'items'=>$yaItems,'receipt'=>$receipt];

			
		}

		if (Yii::$app->request->get('type') == 'promoAdd'){
			$c = Coupon::find()
				->where(['text'=>$reqBody['text'],'stateId'=>1])
				->andWhere(['OR', ['>','dateActiveUntil',date('Y-m-d H:i:s')] , ['is','dateActiveUntil',new \yii\db\Expression('null')] ] )
				->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
				->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
				->asArray()->one();
			
			if(!$c) return ['error'=>'Промокод не найден, либо не активен'];

			if( $c['limitUsages'] && $c['limitUsages'] <= $c['usagesCount']){
				return ['error'=>'Превышено количество использований этого промокода'];
			}

			//if($c['emailBind'] && ( empty($_SESSION['member_email']) || $_SESSION['member_email'] != $c['emailBind']) ) return ['error'=>'Это персональный промокод. Авторизуйтесь в личном кабинете, чтобы использовать его.'];

			$c['excludeProductIds'] = json_decode($c['excludeProductIds'],1);
			$c['includeProductIds'] = json_decode($c['includeProductIds'],1);

			$sumTotal = 0;
			foreach($_SESSION['cart'] as $i=>$d){
				$sumTotal += $d['priceSum']*$d['count'];
				$_SESSION['cart'][$i]['coupon_priceSum'] = null;
			}
	
			if( $c['minOrderSum'] && ( $sumTotal < $c['minOrderSum']  ) ) return ['error'=>'Сумма заказа меньше минимальной'];
			if( $c['maxOrderSum'] && ( $sumTotal > $c['maxOrderSum'] ) ) return ['error'=>'Сумма заказа больше максимальной'];

			$_SESSION['coupon'] = $c;	
			
			$this->calcCartWithCoupon();

			if(!$_SESSION['coupon']){
				return ['error'=>'В корзине нет товаров, для которых можно было бы применить этот промокод.'];
			}

			FingerprintController::addAction('cart_add_promo');

			return [
				'success'=>true,
				'items'=>$_SESSION['cart'],
				'count'=>count($_SESSION['cart']),
				'coupon'=>$this->returnCouponFields(),
			];

		}

		if (Yii::$app->request->get('type') == 'getLink'){

			if(!$_SESSION['cart']) return ['success'=>false,'err'=>'В корзине нет товаров – сохранять нечего.'];

			$item = new Cart;
			$item->hash = md5(date('U').json_encode($_SESSION['cart']));
			$item->cart_arr = json_encode($_SESSION['cart']);
			$item->dateActiveUntil = date("y-m-d H:i:s", strtotime('+7 days'));
			$item->stateId = 1;

			if( isset($_SESSION['member_id']) ){
				$item->createdBy = $_SESSION['member_id'];
			}

			if( isset($_SESSION['coupon']) ){
				$item->coupon_text = $_SESSION['coupon']['text'];
				$item->coupon_arr = json_encode($_SESSION['coupon']);
			}

			if(!$item->save()) return $item->getErrors();

			return ['hash'=>$item->hash];

		}

	}

	private function calcCartWithCoupon(){
		if( empty($_SESSION['coupon']) ) return;
		$c = $_SESSION['coupon'];

		$productIdsCanAdd = [];

		//clean total
		$sumTotal = 0;
		foreach($_SESSION['cart'] as $i=>$d){
			$sumTotal += $d['priceSum']*$d['count'];
			$_SESSION['cart'][$i]['coupon_priceSum'] = null;
		}

		//min and max order sum
		if( $c['minOrderSum'] && ( $c['minOrderSum'] > $sumTotal ) ) $this->removeCoupon();
		if( $c['maxOrderSum'] && ( $c['maxOrderSum'] < $sumTotal ) ) $this->removeCoupon();


		
		// check can
		foreach($_SESSION['cart'] as $i=>$d){

			if( $c['includeProductIds'] && !in_array($i,$c['includeProductIds']) ) continue;
			if( $c['excludeProductIds'] && in_array($i,$c['excludeProductIds']) ) continue;

			$productIdsCanAdd[] = $i;

			if($c['type'] == 'percent'){
				$_SESSION['cart'][$i]['coupon_priceSum'] = round($d['priceSum']/100*(100-$c['amount']), 2);
			}
		}

		if(!$productIdsCanAdd) $this->removeCoupon();
		else if($c['type'] == 'sum' && count($productIdsCanAdd) > 0){
			$sumDiscountPerEach = round($c['amount']/count($productIdsCanAdd),2);

			foreach($productIdsCanAdd as $pId){
				$_SESSION['cart'][$pId]['coupon_priceSum'] = round($_SESSION['cart'][$pId]['priceSum']-$sumDiscountPerEach,2);
			}

		}


		
		
		
	}

	private function removeCoupon(){
		$_SESSION['coupon'] = [];
		foreach($_SESSION['cart'] as $i=>$d){
			$_SESSION['cart'][$i]['coupon_priceSum'] = null;
		}
	}

	private function returnCouponFields(){
		if( !empty($_SESSION['coupon']) ){
			return [
				'text'=>$_SESSION['coupon']['text'],
				'type'=>$_SESSION['coupon']['type'],
				'amount'=>$_SESSION['coupon']['amount'],
			];
		}
		return null;
	}

	private function addCoupon($text){
		$c = Coupon::find()
			->where(['text'=>$text,'stateId'=>1])
			->andWhere(['OR', ['>','dateActiveUntil',date('Y-m-d H:i:s')] , ['is','dateActiveUntil',new \yii\db\Expression('null')] ] )
			->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
			->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
			->asArray()->one();
		
		if(!$c) {
			$this->removeCoupon();
			return ['status'=>false, 'error'=>'Промокод не найден, либо не активен'];
		}

		if( $c['limitUsages'] && $c['limitUsages'] <= $c['usagesCount']){
			$this->removeCoupon();
			return ['status'=>false, 'error'=>'Превышено количество использований этого промокода'];
		}

		$c['excludeProductIds'] = json_decode($c['excludeProductIds'],1);
		$c['includeProductIds'] = json_decode($c['includeProductsIds'],1);

		$sumTotal = 0;
		foreach($_SESSION['cart'] as $i=>$d){
			$sumTotal += $d['priceSum']*$d['count'];
			$_SESSION['cart'][$i]['coupon_priceSum'] = null;
		}

		if( $c['minOrderSum'] && ( $sumTotal < $c['minOrderSum']  ) ) return ['status'=>false,'error'=>'Сумма заказа меньше минимальной'];
		if( $c['maxOrderSum'] && ( $sumTotal > $c['maxOrderSum'] ) ) return ['status'=>false,'error'=>'Сумма заказа больше максимальной'];

		$_SESSION['coupon'] = $c;		
		
		$this->calcCartWithCoupon();

		return ['status'=>true];
	}

	private function loadCartByHash($hash){
		$_SESSION['cart'] = [];
		$_SESSION['coupon'] = [];
		$_SESSION['cart_id'] = null;

		$cart = Cart::find()->where(['hash'=>$hash])->one();
		if(!$cart) return;

		$_SESSION['cart'] = json_decode($cart->cart_arr, 1);

		$_SESSION['cart_id'] = $cart->id;

		if($cart->coupon_text){
			$this->addCoupon($cart->coupon_text);
		}

	}


	private static function HTTPPost($url, array $params) {
        $query = json_encode($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, 1);
    }

	

}
