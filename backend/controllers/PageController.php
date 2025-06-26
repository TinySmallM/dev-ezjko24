<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Page;
use common\models\Chunk;
use common\models\Helpers;

use common\models\CharactsFields;
use common\models\CharactsFieldsValues;

class PageController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['page'],
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

    public function actionAll(){
		
    	return $this->render('all',[]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'get'){
    		$id = $reqBody['id'];
			$item = Page::find()
				->where(['id'=>$id])
				->with(['chunk','charactsfields'])
				->asArray()->one();
			
			foreach($item['charactsfields'] as $i=>$d){
				$item['charactsfields'][$i]['options'] = implode(PHP_EOL, explode('|',$d['options']) );
			}
			
			$item['chunk'] = Helpers::processChunk($item['chunk'],$item['template']);
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		$id = $reqBody['id'];
    		$item = Page::findOne($id);
    		if(!$item) return ['error'=>'Page not found'];
    		
    		foreach(['h1','menuname','parent','deleted','description','menuindex','menushow','template','published','title', 'content', 'url','image'] as $d){
    			$item->$d = $reqBody[$d];
			}
			if(!$item->url) $item->url = Helpers::translit($item->title);
    		if(!$item->save()) return $item->getErrors();
    		
    		
    		foreach($reqBody['chunk'] as $ch_i=>$ch_d){

				if( is_array($ch_d['content']) ) $ch_d['content'] = json_encode($ch_d['content']);
    			
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

			if($item->static_file){
				if($item->published == 1){
					$res = file_get_contents('http://christmedschool.com/'.$item->url);
					mb_convert_encoding($res, 'UTF-8', mb_detect_encoding($res, 'UTF-8, ISO-8859-1', true));
				}
				else $res = '';
				
				file_put_contents(Yii::$app->basePath.'/../frontend/web/static_pages/'.$item->id,$res);
			}
    		
    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){

			$where = ['parent'=>0];
			if( !empty($reqBody['where']) ) $where = $reqBody['where'];

    		$items = Page::find()->select(['id','h1','menuname','deleted','parent'])
    		->with(['child'=>function($q){
    			$q->select(['id','parent','h1','menuname','deleted'])->with(['child'=>function($b){
					$b->select(['id','parent','h1','menuname','deleted']);
				}]);
    		}])->asArray()->where($where)->andWhere(['deleted'=>null])->all();
    		return ['items'=>$items];
		}

		if (Yii::$app->request->get('type') == 'createNew'){
			$item = new Page;

			$item->title = 'Пустая страница';
			$item->h1 = 'Пустая страница';
			$item->menuname = 'Пустая страница';
			$item->template = 2;

			if(!$item->save()) return $item->getErrors(); 

			return ['success'=>true,'id'=>$item->id];
		}
		
		if (Yii::$app->request->get('type') == 'saveSort'){

    		foreach($reqBody['ids'] as $key=>$d){

				$link = Page::find()->where(['id'=>$d,'parent'=>$reqBody['parent']])->one();
				if($link){
					$link->sortId = $key;
					if(!$link->save()) return $link->getErrors();
				}
    			
			}

			return ['success'=>true];
		}

		if (Yii::$app->request->get('type') == 'charactsAdd'){
			$item = new CharactsFields;
			$item->pageId = $reqBody['pageId'];
			$item->nameRu = $reqBody['nameRu'];
			$item->groupName = $reqBody['groupName'];
			$item->type = $reqBody['type'];
			$item->defaultVal = $reqBody['defaultVal'];
			$item->name = bin2hex( random_bytes(5) );
			
			if($reqBody['options']) $item->options = implode('|', explode(PHP_EOL,$reqBody['options']) );

			if(!$item->save()) return $item->getErrors();

			return ['success'=>true,'item'=>$item->getAttributes()];
		}

		if (Yii::$app->request->get('type') == 'charactsSave'){
			$item = CharactsFields::findOne($reqBody['id']);
			if(!$item) return ['error'=>'Поле не найдено'];

			$item->nameRu = $reqBody['nameRu'];
			$item->type = $reqBody['type'];
			$item->defaultVal = $reqBody['defaultVal'];
			$item->groupName = $reqBody['groupName'];

			$ch_used_values = CharactsFieldsValues::find()->where(['fieldId'=>$item->id])->select(['data'])->column();
			$ch_used_values = array_unique($ch_used_values);

			$val_need = [];

			$arr_opts = explode(PHP_EOL,$reqBody['options']);
			foreach($ch_used_values as $val_check){
				if ( !in_array($val_check,$arr_opts) ) $val_need[] = $val_check;
			}

			if($val_need) return ['error'=>'Нельзя удалить значения, которые используются в товарах: '.implode(',',$val_need)];
			
			if($reqBody['options']) $item->options = implode('|',explode(PHP_EOL,$reqBody['options']));

			if(!$item->save()) return $item->getErrors();

			return ['success'=>true,'item'=>$item->getAttributes()];
		}

		if (Yii::$app->request->get('type') == 'charactsRemove'){
			$item = CharactsFields::findOne($reqBody['id']);
			if(!$item) return ['error'=>'Поле не найдено'];

			$item_used = CharactsFieldsValues::find()->where(['fieldId'=>$item->id])->one();
			if($item_used) return ['error'=>'Поле используется в товаре с ID: '.$item_used->productId];

			$item->delete();

			

			return ['success'=>true];
		}
    	
    }

}
