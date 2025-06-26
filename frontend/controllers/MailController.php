<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\MailSubscribe;


class MailController extends Controller {
    
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                	'verify' => ['get'],
                    'api' => ['post']
                ],
            ],
        ];
    }
    public function beforeAction($action){
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
	public function actionVerify(){
		$vId = MailSubscribe::findOne(Yii::$app->request->get('id'));
		if(!$vId || $vId->status != null ) die('Email не существует, либо уже подтвержден.');
		
		if( $this->generateHash( $vId->getAttributes() ) != Yii::$app->request->get('hash') ) die('Не удалось проверить запрос.');
		
		$vId->status = 1;
		$vId->save();
		
		echo('Email подтвержден. Спасибо!');
		
	}


	public function actionApi(){

		$rb = Yii::$app->request->bodyParams;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		if (Yii::$app->request->get('type') == 'addNew'){
			
			if( empty($rb['email']) ) return ['error'=>'Email не указан'];
			if( MailSubscribe::find()->where(['email'=>$rb['email']])->one() ) return ['error'=>'Email уже подтвержден.'];
			
			$ms = new MailSubscribe;
			$ms->email = $rb['email'];
			$ms->status = null;
			$ms->dateCreated = date('Y-m-d H:i:s');
			$ms->fingerprintId = $_SESSION['fingerprint']['id']?$_SESSION['fingerprint']['id']:null;
			if(!$ms->save()) return $ms->getErrors();
			
			
			return ['success'=>true];
			
		}

	}
	
	private function generateHash($model){
		$mHash = md5( json_encode($model).'Mk3kKiifnJk3mkkfnj#ff' );
		return $mHash;
	}

}
