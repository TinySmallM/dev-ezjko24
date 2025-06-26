<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Helpers;
use common\models\Form;
use yii\httpclient\Client as HttpClient;
use yii\helpers\Html;
use frontend\controllers\FingerprintController;

class FormController extends Controller {

    public function beforeAction($action){
    	Helpers::currentRegion();
        $this->enableCsrfValidation = false;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return parent::beforeAction($action);
	}
	
	public function actionSend(){
        
	    $reqBody = Yii::$app->request->post();

        if( empty($reqBody['formId']) || !array_key_exists($reqBody['formId'],Yii::$app->params['form']) ) throw new InvalidArgumentException('Такой формы не существует');
		
        $product = null;
        $date = null;
        $msg = '';
        $data = [];
        
		$form = Yii::$app->params['form'][$reqBody['formId']];
        $fields = [];
   
		foreach($form['fields'] as $name_field => $params_field){
			if($name_field == 'formId' || $name_field == 'page') continue;
			
			if( ( !array_key_exists($name_field, $reqBody) || !$reqBody[$name_field] ) && $params_field['required'] === true ){
                return [
                    'success' => false,
                    'msg' => 'Поле "' . $params_field['name'] . '" обязательно для заполнения.'
                ];
            }
            else if( array_key_exists($name_field, $reqBody) && $reqBody[$name_field]){

                if(strlen($reqBody[$name_field]) > $params_field['maxlength']){
                    throw new InvalidArgumentException('Field ' . $name_field . ' too long');
                }

                //Удаляем все лишнее
                $reqBody[$name_field] = strip_tags( $reqBody[$name_field] );

                //Добавляем в сообщение, если значение не пустое
                //if( in_array($name_field,['name']) ) $msg .= PHP_EOL;
                if($reqBody[$name_field]) $msg .= $params_field['name'] . ': ' . $reqBody[$name_field] .PHP_EOL;
                
            }
            else if( isset($params_field['emptyValue']) ){
                //if( in_array($name_field,['name']) ) $msg .= PHP_EOL;
                $msg .= $params_field['name'] . ': ' . $params_field['emptyValue'] .PHP_EOL;
            }

            /*
            $data[$name_field] = [
			    'ru' => $params_field['name'],
                'val' => $reqBody[$name_field]
            ];
            */
			
		}
        $msg .= PHP_EOL.'======================'.PHP_EOL;
        $msg .= 'Страница: '.(isset($reqBody['page'])?strip_tags($reqBody['page']):'-').PHP_EOL;
        $msg .= 'IP клиента: '.Yii::$app->request->userIP;

        $msgName = $form['name'];
        $msgName .= isset($reqBody['name']) && $reqBody['name'] ? " – ".$reqBody['name']:null;
        $msgName .= isset($reqBody['email']) && $reqBody['email'] ? " [Email.: ".$reqBody['email']."]":null;
        $msgName .= isset($reqBody['phone']) && $reqBody['phone'] ? " [Тел.: ".$reqBody['phone']."]":null;

        $res = mail(Yii::$app->params['mail']['to'], $msgName, $msg ,"From: ".Yii::$app->params['mail']['from']."\r\nBcc: ".Yii::$app->params['mail']['bcc']."\r\n");

        if($res) return ['success'=>true,'msg'=>'Сообщение отправлено.'];
        else return ["success" => false, "err" => 'Что-то пошло не так. Попробуйте нам позвонить.'];

        /*
        $msgSend = Yii::$app->mailer->compose('formSubmit-html', [
            'msg' => $msg,
            'clientIp'=>Yii::$app->request->userIP,
            'page'=>isset($reqBody['page'])?strip_tags($reqBody['page']):'-'
        ])
        ->setFrom(Yii::$app->params['mail']['from'])
        ->setTo(Yii::$app->params['mail']['to'])
        ->setSubject('Новое сообщение через форму обратной связи');

        $res = $msgSend->send();
        if( $res ){
            return $res;
            return ['success'=>true,'msg'=>'Сообщение отправлено.'];
        }
        */

        /*
		//Сохраняем на серваке
		$item = new Form;
		$item->dateCreated = date("U");
		$item->formId = $form_id;
		$item->data = $msg;
		$item->clientIp = Yii::$app->request->userIP;
		
		$item->save();

        $data['domain'] = [
		    'ru' => 'Регион',
            'val' => Url::home(true)
        ];

		$params = [
		    'token' => Yii::$app->params['region']['current']['statsToken'],
		    'data' => $data
		];
        */


		/*
            $curl = curl_init('https://stats.seovolga.ru/admin/service/mail?type=addItem');
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-type: application/json"]);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
            $res = curl_exec($curl);
            curl_close($curl);

            $res = json_decode($res, true);

            if(!empty($res['id'])){
                return [
                    'success' => true,
                    'msg' => 'Заявка отправлена.' . PHP_EOL . 'Номер: #' . $item->id
                ];
            }
        */
		
		/* Google Recaptcha v3 */
		//$r_query = file_get_contents("https://www.google.com/recaptcha/api/siteverify?response=".$_REQUEST['google_token'].'&secret='.Yii::$app->params['recaptcha']['secret']);
		//$r_query = json_decode($r_query);
		
		//if(empty($r_query->score) || $r_query->score < 0.3) {
		//    return ["success" => false, "err" => 'Проверка не пройдена. Свяжитесь, пожалуйста, с нами.', 'score' => $r_query->score];
		//}
		
		FingerprintController::addAction('form_send');

		return ["success" => false, "msg" => 'Что-то пошло не так. Попробуйте нам позвонить.'];		
	}
	

}
