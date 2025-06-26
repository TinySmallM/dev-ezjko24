<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\User;

class StatsController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','manager'],
                        'allow' => true,
                    ],
                ],
            ]
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
        $reqBody = Yii::$app->request->bodyParams;
	    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    
        $this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}


    public function actionLogin(){
        
        if( Yii::$app->request->get('token') ){
            $token = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',Yii::$app->request->get('token') );
            if( !file_exists(\Yii::$app->basePath.'/runtime/stats_auth/'.$token) ) die();
            
            $uid = file_get_contents(\Yii::$app->basePath.'/runtime/stats_auth/'.$token);
            unlink(\Yii::$app->basePath.'/runtime/stats_auth/'.$token);
            
            $u = User::findOne($uid);
            Yii::$app->user->login($u);
            
            return $this->redirect('/master');

        }
        
        //if( Yii::$app->getRequest()->getUserIP() != '46.254.20.203' ) die(); 
        
        //Удаляем старые сессии
        if (file_exists(\Yii::$app->basePath.'/runtime/stats_auth/')) {
            foreach (glob(\Yii::$app->basePath.'/runtime/stats_auth/*') as $file) {
                if( date('U') - date("U", filemtime($file)) > 600 ) unlink($file);
            }
        }
	
        $str = md5(random_bytes(15));
        $u = User::find()->where(['username'=>Yii::$app->request->get('name')])->orWhere(['email'=>Yii::$app->request->get('name')])->one();
        if( !$u ) return ['success'=>false,'error'=>'User nof exists'];
        
        if( !is_dir(\Yii::$app->basePath.'/runtime/stats_auth/') ) {
            mkdir(\Yii::$app->basePath.'/runtime/stats_auth/', 0755, true);
        }
        
        file_put_contents(\Yii::$app->basePath.'/runtime/stats_auth/'.$str,$u->id);
        
        return ['success'=>true,'token'=>$str];
    }
    
    public function actionManager(){
        //if( !$this->verifyStats() ) die();
        
        if( Yii::$app->request->get('type') == 'user.edit' ) {
            $u = User::find()->where(['username'=>Yii::$app->request->get('name')])->orWhere(['email'=>Yii::$app->request->get('name')])->one();
            if(!$u) return ['error'=>'User not exists'];
            
            if( Yii::$app->request->get('password') ) $u->setPassword( Yii::$app->request->get('password') );
            if( Yii::$app->request->get('email') ) $u->email = Yii::$app->request->get('email');
            if( Yii::$app->request->get('status') ) $u->status = Yii::$app->request->get('status');
            if( Yii::$app->request->get('password_reset_token') ) $u->password_reset_token = Yii::$app->request->get('password_reset_token');
            if( $u->save() ) return ['success'=>true];
        }
        
        if( Yii::$app->request->get('type') == 'user.getOne' ) {
            $u = User::find()->where(['username'=>Yii::$app->request->get('name')])->orWhere(['email'=>Yii::$app->request->get('name')])->asArray()->one();
            return ['item'=>$u];
        }
        
        if( Yii::$app->request->get('type') == 'user.getAll' ) {
            $u = User::find()->asArray()->all();
            return ['item'=>$u];
        }
    }
    
    private function verifyStats(){
        //if( Yii::$app->getRequest()->getUserIP() != '46.254.20.203' ) return false;
        if( !Yii::$app->request->get('token') ) return false;
        
        $res=file_get_contents('https://stats.seovolga.ru/admin/service/verify?token='.Yii::$app->request->get('token').'&host='.$_SERVER['HTTP_HOST']); 
        if($res != 1) return false;
        
        return true;
    }

}
