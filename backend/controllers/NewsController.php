<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\News;

class NewsController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['news'],
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
    		$item = News::find()->where(['id'=>$id])->asArray()->one();
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		
    		if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
    			$item = News::findOne($reqBody['id']);
    			if(!$item) return ['error'=>'News not found'];
    		}
    		else $item = new News;
    		
    		
    		foreach(['name','content','image','isPublished'] as $d){
    			$item->$d = $reqBody[$d];
    		}
    		if(!$item->save()) return $item->getErrors();

    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){
    		$items = News::find()->select(['id','name','content','isPublished','isDeleted'])
    			->where(['isDeleted'=>null])->orderBy(['id'=>SORT_DESC])->asArray()->all();
    		return ['items'=>$items];
    	}
    	
    }

}
