<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Order;
use common\models\OrderItem;
use frontend\controllers\CartController;

class OrderController extends Controller
{
	
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','index','api'],
                        'roles' => ['order'],
                        'allow' => true
                    ],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function beforeAction($action){
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
	public function actionAll(){
    	
    	$items = Order::find()->asArray()->orderBy(['id'=>SORT_DESC])->all();
    	
    	return $this->render('all',['items'=>$items]);
        
    }

    public function actionIndex($id){
    	
    	$item = Order::find()->where(['id'=>$id])->with(['items'])->asArray()->one();
    	if(!$item) return 'Заказ не найден';
    	
    	return $this->render('index',['item'=>$item]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'get'){
    		$id = $reqBody['id'];
    		
    		$item = Order::find()->where(['id'=>$id])->with(['items','fingerprint'=>function($f){
    			$f->with(['action']);
    		}])->asArray()->one();
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		
    		$id = $reqBody['id'];
    		$item = Order::findOne($id);
    		if(!$item) return ['error'=>'Item not found'];
    		
    		foreach(['orgType','name','email','phone','delivery','payMethod','comment','status'] as $d){
    			$item[$d] = $reqBody['order'][$d];
    		}
    		
			
			//OrderItem::deleteAll(['order_id'=>$id]); 
			
			$sumAll = 0;
    		foreach($reqBody['order']['items'] as $d){
    			
    			$oi = OrderItem::findOne($d['id']);
    			
    			if($d['quantity'] < 1){
    				$oi->delete();
    				continue;
    			}

    			$oi->name = $d['name'];
    			$oi->quantity = $d['quantity'];
    			$oi->priceSum = $d['priceSum'];
    			$oi->priceType = $d['priceType'];
    			$oi->cost = $d['cost'];
    			$oi->nds = $d['nds'];
    			$oi->priceName = $d['priceName'];
    			if(!$oi->save()){
    				return $oi->getErrors();
    			}
    			
    			$sumAll += $d['cost'];
    		}
    		$item->amount = round($sumAll, 2);
    		$item->save();
    		
    		return ['success'=>true];
    	}

		if (Yii::$app->request->get('type') == 'sendEmailComplete'){
    		$id = $reqBody['id'];

			$order = Order::findOne($id);

			$oi = OrderItem::find()->where(['order_id'=>$order->id])->asArray()->all();
	
			$artikuls = array_column($oi,'artikul');
			if($artikuls){
				$platformRes = CartController::generateForArtikuls($order->name,$order->email,$artikuls);
				$order->platformCode = $platformRes['code'];
				$order->save();
			}

    		
    		exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii mail/order '.$id.' status > /dev/null 2>/dev/null &');
    		
    		return ['success'=>true];
    	}
    	
    }

}
