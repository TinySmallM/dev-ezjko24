<?php
	namespace console\controllers;
	
	use Yii;
	use yii\console\Controller;
	use yii\httpclient\Client;
	use yii\Helpers\ArrayHelper;
	use common\models\Order;
	use common\models\Helpers;
	use common\models\Chunk;
	use common\models\Coupon;
	
	class MailController extends Controller {
		
		public function actionOrder($id, $type){
			
			$o = Order::find()->where(['id'=>$id])->with(['items'])->asArray()->one();
			
			if($type == 'new'){
				Yii::$app->mailer->view->params['email_to'] = $o['email'];
				Yii::$app->mailer->compose('orderNew-html',['o'=>$o])
					->setFrom(Yii::$app->params['mail']['from'])
				    ->setTo($o['email'])
				    ->setSubject('Заказ #'.$o['id'].' в магазине '.Yii::$app->params['projectName'])
				    ->send();
				
				Yii::$app->mailer->compose('orderNewManager-html',['o'=>$o])
				->setFrom(Yii::$app->params['mail']['from'])
				    ->setTo(Yii::$app->params['mail']['to'])
				    ->setSubject('Заказ #'.$o['id'].' в магазине '.Yii::$app->params['projectName'])
				    ->send();
			}
			
			/*if($type == 'status'){
				if($o['status'] == 3){
					
					Yii::$app->mailer->view->params['email_to'] = $o['email'];
					Yii::$app->mailer->compose('orderStatus-html',['o'=>$o])
						->setFrom(Yii::$app->params['mail']['from'])
						->setTo( trim($o['email']) )
						->setSubject('Заказ #'.$o['id'].' в магазине '.Yii::$app->params['projectName'])
						->send();

					

					$msg->send();
					
				}
			}
			
			if($type == 'paymentOk'){
				Yii::$app->mailer->view->params['email_to'] = $o['email'];
				Yii::$app->mailer->compose('orderPaymentOk-html',['o'=>$o])
					->setFrom(Yii::$app->params['mail']['from'])
				    ->setTo( trim($o['email']) )
				    ->setSubject('Заказ #'.$o['id'].' в магазине '.Yii::$app->params['projectName'])
				    ->send();
			}*/
		}

		public function actionSubscribe($id){
			
			$o = Order::find()->where(['id'=>$id])/*->with(['items'])*/->asArray()->one();

			//file_get_contents('https://api.unisender.com/ru/api/subscribe?format=json&api_key=****&list_ids=2&fields[email]='.$o['email'].'&double_optin=3');
		}

		public function actionCouponpersonal($id){
			$c = Coupon::find()->where(['id'=>$id])->asArray()->one();

			Yii::$app->mailer->view->params['email_to'] = $c['emailBind'];

			$msg = Yii::$app->mailer->compose('couponpersonal-html',['c'=>$c])
					->setFrom(Yii::$app->params['mail']['from'])
				    ->setTo($c['emailBind'])
				    ->setSubject('🚀 Получен промокод на скидку '.round($c['amount']).($c['type']=='percent'?'%':'РУБ').'! Успей использовать, пока он не сгорел 🔥')
				    ->send();

		}

		
	}