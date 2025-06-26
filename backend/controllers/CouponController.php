<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Order;
use common\models\OrderItem;
use common\models\Coupon;

class CouponController extends Controller
{
	
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','api'],
                        'roles' => ['coupon'],
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
	
	public function actionIndex(){
    	
    	$items = Order::find()->asArray()->orderBy(['id'=>SORT_DESC])->all();
    	
    	return $this->render('index',['items'=>$items]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'getItems'){

			$w = [];

			if( isset($reqBody['where']) ){
				if( isset($reqBody['where']['groupType']) ){

					if($reqBody['where']['groupType'] == 'auto'){
						$w = ['LIKE','groupType','auto'];
					}

					if($reqBody['where']['groupType'] == 'hand'){
						$w = ['NOT LIKE','groupType','auto'];
					}

				}
			}

    		$items = Coupon::find()->where($w)->asArray()->orderBy(['id'=>SORT_DESC])->all();

			foreach($items as $i=>$d){
				$items[$i]['includeProductIds'] = json_decode($items[$i]['includeProductIds']);
				$items[$i]['excludeProductIds'] = json_decode($items[$i]['excludeProductIds']);
			}

    		return ['items'=>$items];
    	}

		if (Yii::$app->request->get('type') == 'save'){

			$item = Coupon::findOne($reqBody['id']);

			if( array_key_exists('text',$reqBody) && $item->text != $reqBody['text'] && Coupon::find()->where(['text'=>$reqBody['text']])->one() ) return ['error'=>'Купон с таким названием уже существует'];

			foreach($reqBody as $rb_i=>$rb_d){

				if($rb_i  == 'id') continue;

				if($rb_i == 'includeProductIds' || $rb_i == 'excludeProductIds') $rb_d = json_encode($rb_d);
				if( ($rb_i == 'dateCreated' || $rb_i == 'dateActiveUntil') && $rb_d ) $rb_d = date("y-m-d H:i:s", strtotime($rb_d ) );
				
				if($rb_i == 'stateId' && $rb_d == 'delete'){ // удаление
					$item->delete();
					return ['success'=>true,'item'=>[]];
				}
				
				$item->$rb_i = $rb_d;
			}

			if( !$item->save() ) return $item->getErrors();
			return ['success'=>true,'item'=>$item->getAttributes()];
		}

		if (Yii::$app->request->get('type') == 'createNew'){
			$item = new Coupon;
			$item->text = $this->generateText();
			$item->type = 'percent';
			$item->amount = 0;
			$item->maxOrderSum = null;
			$item->minOrderSum = null;
			
			if( !$item->save() ) return $item->getErrors();
			return ['success'=>true,'item'=>$item->getAttributes()];
		}

		if (Yii::$app->request->get('type') == 'addPersonal'){

			$groupType = 'hand';
			if( isset($reqBody['groupType']) ) $groupType = $reqBody['groupType'];

			$sum = 0;
			if($reqBody['amount'] > 10000){
				$res = Coupon::addPersonal(25,'percent','+7 days',$groupType,$reqBody['email'],true);
				$sum = 25;
			}
			else if($reqBody['amount'] > 5000){
				$res = Coupon::addPersonal(15,'percent','+7 days',$groupType,$reqBody['email'],true);
				$sum = 15;
			}
			else if($reqBody['amount'] > 1000){
				$res = Coupon::addPersonal(10,'percent','+7 days',$groupType,$reqBody['email'],true);
				$sum = 10;
			}

			return ['success'=>$res,'sum'=>$sum];
		}

		if (Yii::$app->request->get('type') == 'addPersonalMass'){

			$ord = Order::find()
				->select(['email'])
				->where(['>','created','2023-09-06'])
				->andWhere(['status'=>3])
				//->andWhere(['email'=>'contact@smedia.one'])
				->column();
			
			$ord = array_map('strtolower', $ord);
			$ord = array_unique($ord);

			$data = [];

			foreach($ord as $m){

				if($m['amount'] > 10000){
					$data[$m] = Coupon::addPersonal(25,'percent','+48 hours',$m['email'],true);
				}
				else if($m['amount'] > 5000){
					$data[$m] = Coupon::addPersonal(15,'percent','+48 hours',$m['email'],true);
				}
				else if($m['amount'] > 1000){
					$data[$m] = Coupon::addPersonal(10,'percent','+48 hours',$m['email'],true);
				}
			}


			return $data;


			

			return ['success'=>$res];
		}



    	
    }

	private function generateText($length = 5){
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

}
