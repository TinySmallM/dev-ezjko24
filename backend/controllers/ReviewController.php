<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Review;

class ReviewController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['review'],
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
    		$item = Review::find()->where(['id'=>$id])->asArray()->one();
			$item['image'] = json_decode($item['image'],1);
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		
    		if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
    			$item = Review::findOne($reqBody['id']);
    			if(!$item) return ['error'=>'Review not found'];
    		}
    		else {
    			$item = new Review;
    			$item->image = '[]';
    		}

    		foreach(['productId','stars','dateCreated','isPublished','regionId','image','doc','text','fio'] as $d){
    			$item->$d = $reqBody[$d];
    			
    			
    			if($d == 'image'){
    				if( $reqBody[$d] ) $item->image = json_encode($reqBody[$d]);
    			}
    			
    			
    		}
    		if(!$item->save()) return $item->getErrors();

    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){
    		$items = Review::find()->orderBy(['id'=>SORT_DESC])->asArray()->all();
			
			foreach($items as $i=>$d){
				$items[$i]['image'] = json_decode($d['image'],1);
			}

    		return ['items'=>$items];
    	}
    	
    }

}
