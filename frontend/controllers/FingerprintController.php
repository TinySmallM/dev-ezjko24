<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Fingerprint;
use common\models\FingerprintAction;
use common\models\Helpers;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Language;


class FingerprintController extends Controller {
    
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'api' => ['post']
                ],
            ],
        ];
    }
    public function beforeAction($action){
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	//API
	public function actionApi(){
		
		session_start();
		if( empty($_SESSION['fingerprint']) ) $_SESSION['fingerprint'] = [];
		
		
		$rb = Yii::$app->request->bodyParams;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		if (Yii::$app->request->get('type') == 'collect'){
			
			$skip = ['id','uid_backend'];
			
			foreach($rb as $key=>$d){
				if( in_array($key,$skip) ) continue;
				if($key == 'uid') $d = strval($d);
				if($key == 'canvas' && $d) $d = md5($d);
				$_SESSION['fingerprint'][$key.'_frontend'] = $d;
			}
			
			$this->saveDb();
			
			return ['uid_backend'=>$_SESSION['fingerprint']['uid_backend']];
			
			
			
		}



	}
	
	public function collectBackend(){
		session_start();
		if( empty($_SESSION['fingerprint']) ) $_SESSION['fingerprint'] = [];
		if( empty($_SESSION['fingerprint_visit']) ) $_SESSION['fingerprint_visit'] = [];
		//$_SESSION['fingerprint'] = [];
		
		$_SESSION['fingerprint_visit']['referer'] = $_SERVER['HTTP_REFERER'];
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$os = new Os();
		$browser = new Browser();
		$device = new Device();
		$language = new Language();
		
		$_SESSION['fingerprint']['os_backend'] = $os->getName().' '.$os->getVersion();
		$_SESSION['fingerprint']['device_backend'] = $device->getName()!='unknown'?$device->getName():null;
		$_SESSION['fingerprint']['user_agent_backend'] = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['fingerprint']['browser_backend'] = $browser->getName().' '.$browser->getVersion();
		$_SESSION['fingerprint']['is_robot_backend'] = $browser->isRobot()?1:null;
		$_SESSION['fingerprint']['lang_backend'] = $language->getLanguage();
		$_SESSION['fingerprint']['ip_backend'] = $ip;
		$_SESSION['fingerprint']['device_type_backend'] = Helpers::checkDevice();
		$_SESSION['fingerprint']['url_frontend'] = $_SERVER['REQUEST_URI'];
		
	}
	
	public function saveDb(){
		
		session_start();
		if( empty($_SESSION['fingerprint']) ) $_SESSION['fingerprint'] = [];
		
		$sfp = $_SESSION['fingerprint'];
		
		//Пытаемся найти
		$findWhere = ['OR'];
		if( isset($sfp['id']) ) $findWhere[] = ['id'=>$sfp['id']];
		if( isset($sfp['ya_client_id_frontend']) ) $findWhere[] = ['ya_client_id_frontend'=>$sfp['ya_client_id_frontend']];
		if( isset($sfp['hash']) ) $findWhere[] = ['hash'=>$rb['hash']];
		if( isset($sfp['uid_frontend']) ) $findWhere[] = ['uid_frontend'=>$sfp['uid_frontend']];
		if( isset($sfp['uid_backend']) ) $findWhere[] = ['uid_backend'=>$sfp['uid_backend']];
		
		if( count($findWhere) > 1 ){
			$f = Fingerprint::find()->where($findWhere)->one();
		}
		
		
		//Создаем, если не нашли
		if(!$f) {
			$f = new Fingerprint;
			$f->uid_backend = md5(date('U').$_SERVER['REMOTE_ADDR'].'Kkll3jrfklk34nN');
		}
		
		foreach($_SESSION['fingerprint'] as $key=>$val){
			if($f && $key == 'id' ) continue;
			if($key == 'url_frontend') continue;
			$f->$key = $val;
		}
		
		if( $f->save() ) {
			$_SESSION['fingerprint']['id'] = $f->id;
			$_SESSION['fingerprint']['uid_backend'] = $f->uid_backend;
		}
		else print_r($f->getErrors());
		
		if( isset($_SESSION['fingerprint']['url_frontend']) ){
			$this->addAction('visit');
		}
		
		return true;
		

	}
	
	public function addAction($type='visit'){

		if( empty($_SESSION['fingerprint']['id']) ) return;
		
		session_start();
		if( empty($_SESSION['fingerprint_visit']) ) {
			$_SESSION['fingerprint_visit'] = [
				'dateLastAction'=>date('U')
			];
		}
		
		$_SESSION['fingerprint_visit']['url'] = $_SESSION['fingerprint']['url_frontend'];
		$_SESSION['fingerprint_visit']['fingerprintId'] = $_SESSION['fingerprint']['id'];
		
		$url_parsed = parse_url($_SESSION['fingerprint_visit']['url']);
		
		$fpa = new FingerprintAction;
		$fpa->url = $url_parsed['scheme'].'://'.$url_parsed['host'].$url_parsed['path'];
		$fpa->type = $type;
		$fpa->fingerprintId = $_SESSION['fingerprint_visit']['fingerprintId'];
		$fpa->dateCreated = date('U');
		$fpa->referer = $_SESSION['fingerprint_visit']['referer'];
		$fpa->params = $url_parsed['fragment']?'#'.$url_parsed['fragment']:null.$url_parsed['query'];
		
		if( ( date('U') - $_SESSION['fingerprint_visit']['dateLastAction'] < 900 ) && isset($_SESSION['fingerprint_visit']['parentId']) ){
			$fpa->groupId = $_SESSION['fingerprint_visit']['parentId'];
		}
		
		if( $fpa->save() ) {
			if( !$fpa->groupId ) $_SESSION['fingerprint_visit']['parentId'] = $fpa->id;
		}
		else return print_r($fpa->getErrors());
		
	}
	
	private function getUtm(){
		
		$utm_all = '';
		
		foreach($_GET as $key=>$val){
			if( strpos($key,'utm_') > -1) $utm_all .= '&'.$key.'='.$val;
		}
		
		return $utm_all?$utm_all:null;
	}

	
	

	

}
