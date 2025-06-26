<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Template;

class TemplateController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['template'],
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
    	
    	return $this->render('all',[]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'get'){
    		$id = $reqBody['id'];
    		$item = Template::find()->where(['id'=>$id])->with(['block'])->asArray()->one();
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		
    		if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
    			$item = Template::findOne($reqBody['id']);
    			if(!$item) return ['error'=>'Template not found'];
    		}
    		else {
    			$item = new Template;
    		}

    		foreach(['name','file','description'] as $d){
    			$item->$d = $reqBody[$d];
    		}
    		
    		$item->dateEdited = date('U');
    		
    		if(!$item->save()) return $item->getErrors();

    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){
    		$items = Template::find()->orderBy(['id'=>SORT_DESC])->asArray()->all();
    		return ['items'=>$items];
    	}
    	
    }

}
