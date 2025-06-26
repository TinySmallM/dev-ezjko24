<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\PlatformPage;
use common\models\Chunk;
use common\models\Helpers;
use common\models\Page;

class PlatformController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['page','api'],
                        'roles' => ['platform'],
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
		//Helpers::appGlobals();
		return parent::beforeAction($action);
	}

    public function actionPage(){
    	
    	return $this->render('page',[]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'get'){
    		$id = $reqBody['id'];
			$item = PlatformPage::find()->where(['id'=>$id])/*->with(['chunk'])*/->asArray()->one();
			
			//$item['chunk'] = Helpers::processChunk($item['chunk'],$item['template']);
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		$id = $reqBody['id'];
    		$item = PlatformPage::findOne($id);
    		if(!$item) return ['error'=>'Page not found'];

			if( isset($reqBody['remove']) ) {
				$item->delete();
				return ['success'=>true];
			}

    		
    		foreach(['h1','content','file','subjectId','courseId'] as $d){
    			$item->$d = $reqBody[$d];
			}

			$item->save();


    		
    		return ['success'=>true,'id'=>$item->id];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){

			//$where = ['parent'=>0];
			if( !empty($reqBody['where']) ) $where = $reqBody['where'];

    		$items = PlatformPage::find()->where($where)->orderBy(['sortId'=>SORT_ASC])->asArray()->all();
    		return ['items'=>$items];
		}

		if (Yii::$app->request->get('type') == 'getParentPage'){

			$data['subject'] = Page::find()->where(['template'=>2])->orWhere(['id'=>1099])->asArray()->all();
			//$data['subject'] = ArrayHelper::index($data['subject'],'id');
			$data['course'] = Page::find()->where(['template'=>[3,4,13,14]])->orWhere(['id'=>1099])->asArray()->all();
    		return $data;
		}

		if (Yii::$app->request->get('type') == 'saveSort'){

    		foreach($reqBody['ids'] as $key=>$d){

				$link = PlatformPage::find()->where(['id'=>$d])->one();
				if($link){
					$link->sortId = $key;
					if(!$link->save()) return $link->getErrors();
				}
    			
			}

			return ['success'=>true];
		}

		if (Yii::$app->request->get('type') == 'createNew'){
			$item = new PlatformPage;

			$item->h1 = 'Пустая страница';
			$item->file = 'Пустая страница';
			$item->content = '';

			if(!$item->save()) return $item->getErrors(); 

			return ['success'=>true,'id'=>$item->id];
		}

    	
    }

}
