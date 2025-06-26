<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Region;

class RegionController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['region'],
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
    		$item = Page::find()->where(['id'=>$id])->with(['chunk'])->asArray()->one();
    		
    		$item['chunk'] = ArrayHelper::index($item['chunk'], 'name');
    		
    		$chunks = Yii::$app->params['template'][$item['template']]['chunk'];
    		foreach($chunks as $ch_i=>$ch_d){
    			if( !array_key_exists($ch_i,$item['chunk']) ) {
    				$item['chunk'][$ch_i]['content'] = '';
    				$item['chunk'][$ch_i]['id'] = null;
    			}
    			
    			$item['chunk'][$ch_i]['name'] = $ch_d['name'];
    			$item['chunk'][$ch_i]['type'] = $ch_d['type'];
    		}
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		$id = $reqBody['id'];
    		$item = Page::findOne($id);
    		if(!$item) return ['error'=>'Page not found'];
    		
    		foreach(['h1','menuname','parent','deleted','description','menuindex','menushow','template','published','title', 'content'] as $d){
    			$item->$d = $reqBody[$d];
    		}
    		if(!$item->save()) return $item->getErrors();
    		
    		
    		foreach($reqBody['chunk'] as $ch_i=>$ch_d){
    			
    			$ch = Chunk::find()->where(['name'=>$ch_i,'itemType'=>1,'itemId'=>$item->id])->one();
    			if(!$ch) {
    				$ch = new Chunk;
    				$ch->itemId = $item->id;
    				$ch->itemType = 1;
    				$ch->name = $ch_i;
    			}
    			$ch->content = $ch_d['content'];
    			if(!$ch->save()) return $ch->getErrors();
    		}
    		
    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){
    		$items = Page::find()->select(['id','h1','menuname','deleted'])
    		->with(['child'=>function($q){
    			$q->select(['id','parent','h1','menuname','deleted']);
    		}])->asArray()->where(['deleted'=>null,'parent'=>0])->all();
    		return ['items'=>$items];
    	}
    	
    }

}
