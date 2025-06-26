<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Product;
use common\models\PageProduct;
use common\models\Page;
use common\models\Chunk;
use common\models\ProductImage;
use common\models\ProductCharacts;
use yii\helpers\ArrayHelper;
use common\models\Helpers;
use common\models\ServPredicate;
use common\models\CharactsFields;
use common\models\CharactsFieldsValues;

class ProductController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['product'],
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
    	
		if(Yii::$app->request->get('ver') == 2) return $this->render('all2',[]);

    	return $this->render('all2',[]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'get'){
    		$id = $reqBody['id'];
    		$item = Product::find()->where(['id'=>$id])->with(['charactsfieldsvalues','chunk','gallery','page'=>function($q){
    			$q->select(['id'])->column();
    		}])->asArray()->one();
    		
    		$item['page'] = ArrayHelper::getColumn($item['page'], 'id');
			$item['chunk'] = Helpers::processChunk($item['chunk'],$item['template']);
			$item['charactsfieldsvalues'] = ArrayHelper::index($item['charactsfieldsvalues'],'fieldId');

			$item['charactsfields'] = CharactsFields::find()->where(['pageId'=>$item['page']])->asArray()->all();

			foreach($item['charactsfields'] as $i=>$d){
				$item['charactsfields'][$i]['options'] = explode('|',$d['options']);
			}

			foreach($item['charactsfields'] as $i=>$d){
				$item['charactsfields'][$i]['charactsfieldsvalues__id'] = isset($item['charactsfieldsvalues'][$d['id']])?$item['charactsfieldsvalues'][$d['id']]['id']:null;
				$item['charactsfields'][$i]['charactsfieldsvalues__data'] = isset($item['charactsfieldsvalues'][$d['id']])?$item['charactsfieldsvalues'][$d['id']]['data']:null;
			}
    		
    		return ['item'=>$item];
    	}
    	
    	if (Yii::$app->request->get('type') == 'save'){
    		
    		if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
    			$item = Product::findOne($reqBody['id']);
    			if(!$item) return ['error'=>'Product not found'];
    		}
    		else $item = new Product;
    		
    		foreach(['title','h1','description','menuindex','menushow','template','menuname','image','price1_sum','price1_name','price2_sum','price2_name','content','published','deleted','url','artikul1','artikul2'] as $d){
    			$item->$d = $reqBody[$d];
			}
    		if(!$item->save()) return $item->getErrors();
    		$item->id = $item->id;
    		
    		//Сохраняем URL
    		if(!$item->url) $item->url = Helpers::translit($item->title).'-id'.$item->id;
    		if(!$item->save()) return $item->getErrors();
			
			//Обновляем чанки
    		foreach($reqBody['chunk'] as $ch_i=>$ch_d){
    			
    			$ch = Chunk::find()->where(['name'=>$ch_i,'itemType'=>2,'itemId'=>$item->id])->one();
    			if(!$ch) {
    				$ch = new Chunk;
    				$ch->itemId = $item->id;
    				$ch->itemType = 2;
    				$ch->name = $ch_i;
				}
				
				if( is_array($ch_d['content']) ) $ch_d['content'] = json_encode($ch_d['content']);
    			$ch->content = $ch_d['content'];
    			if(!$ch->save()) return $ch->getErrors();
    		}
    		
    		//Обновляем связи с категориями
    		PageProduct::deleteAll(['AND',['productId' =>$item->id],['NOT IN','pageId',$reqBody['page']]]); //Удаляем все, которых нет в списке
    		foreach($reqBody['page'] as $d){
    			$link = PageProduct::find()->where(['productId'=>$item->id,'pageId'=>$d])->one();
				if(!$link){
					$link = new PageProduct;
					$link->productId = $item->id;
    				$link->pageId = $d;
    				if(!$link->save()) return $link->getErrors();
				}
    			
			}
			
			//Обновляем галерею
			foreach($reqBody['gallery'] as $g_i=>$g_d){
				$gitem = ProductImage::find()->where(['productId'=>$item->id,'image'=>$g_d['image']])->one();
    			if(!$gitem) {
    				$gitem = new ProductImage;
					$gitem->productId = $item->id;
					$gitem->image = $g_d['image'];
				}
				
				$gitem->orderId = $g_i;
				$gitem->description = $g_d['description'];
				if(!$gitem->save()) return $gitem->getErrors();
			}
			$gImgs = ArrayHelper::getColumn($reqBody['gallery'], 'image');
			ProductImage::deleteAll(['AND',['productId'=>$item->id],['NOT IN','image',$gImgs]]);

			//Обновляем характеристики
			$characts_arr = [];
			foreach($reqBody['charactsfields'] as $g_i=>$g_d){

				$el = CharactsFieldsValues::find()->where(['productId'=>$item->id,'fieldId'=>$g_d['id']])->one();
				if(!$el){
					$el = new CharactsFieldsValues;
					$el->productId = $item->id;
					$el->fieldId = $g_d['id'];
					$el->fieldName = $g_d['name'];
					$el->save();
				}

				$el->data = $g_d['charactsfieldsvalues__data'];
				
				if(!$el->save()) return $el->getErrors();

				if( $el->data ) $characts_arr[] = $g_d['name'] .'=>'.$el->data;
				else if($g_d['defaultValue']){
					$characts_arr[] = $g_d['name'] .'=>'.$g_d['defaultValue'];
				}
			}

			if($characts_arr){
				$item->characts = implode('|',$characts_arr);
				$item->save();
			}
    		
    		return ['success'=>true];
    	}
    	
    	if (Yii::$app->request->get('type') == 'getAll'){
    		
			$prItems = Product::find()
				->select(['id','h1','menuname','deleted'])
				->asArray()->all();

			if($reqBody['out'] == 'raw'){
				return ['items'=>$prItems];
			}
    		
    		$items = Page::find()->select(['id','h1','menuname','deleted','template','url'])
				->asArray()->where(['deleted'=>null])->all();

			foreach($items as $i=>$d){
				$items[$i]['product'] = Product::find()
				->select(['product.id','h1','menuname','deleted','published'])
				->innerJoin('page_product pp', 'pp.productId = product.id ')
				->where(['pp.pageId'=>$d['id']])
				->orderBy(['pp.sortId'=>SORT_ASC])
				->asArray()->all();
			}
    		
    		$idProduct = PageProduct::find()->select(['productId'])->distinct()->column();
    		$notCat = Product::find()->select(['id','h1','menuname','deleted'])->where(['NOT IN','id',$idProduct])->asArray()->all();
    		array_unshift($items, [
    			'id'=> "0",
				'h1'=> "Нераспределенные",
				'menuname'=> "Нераспределенные",
				'template'=> "1",
				'deleted'=> null,
				'product'=> $notCat,
    		]);
    		
    		return ['items'=>$items,'items_all'=>$prItems];
		}

		if (Yii::$app->request->get('type') == 'getSearch'){

			$str = $reqBody['query'];

			$cond1 = ['AND'];
			$cond2 = ['AND'];
			$cond3 = ['AND'];
			$cond4 = ['AND'];
			$cond5 = ['AND'];
			foreach(explode(' ',$str) as $s){
			  $cond1[] = ['LIKE','content',$s];
			  $cond2[] = ['LIKE','title',$s];
			  $cond3[] = ['LIKE','h1',$s];
			  $cond4[] = ['LIKE','url',$s];
			}

			if( $reqBody['filters']['not_published'] ){
				$cond1[] = ['is','published',new \yii\db\Expression('null')];
				$cond2[] = ['is','published',new \yii\db\Expression('null')];
				$cond3[] = ['is','published',new \yii\db\Expression('null')];
				$cond4[] = ['is','published',new \yii\db\Expression('null')];
				$cond5[] = ['is','published',new \yii\db\Expression('null')];
			}

			if( $reqBody['filters']['not_image'] ){
				$cond1[] = ['is','image',new \yii\db\Expression('null')];
				$cond2[] = ['is','image',new \yii\db\Expression('null')];
				$cond3[] = ['is','image',new \yii\db\Expression('null')];
				$cond4[] = ['is','image',new \yii\db\Expression('null')];
				$cond5[] = ['is','image',new \yii\db\Expression('null')];
			}

			if( $reqBody['filters']['not_price'] ){
				$cond1[] = ['is','price1_sum',new \yii\db\Expression('null')];
				$cond2[] = ['is','price1_sum',new \yii\db\Expression('null')];
				$cond3[] = ['is','price1_sum',new \yii\db\Expression('null')];
				$cond4[] = ['is','price1_sum',new \yii\db\Expression('null')];
				$cond5[] = ['is','price1_sum',new \yii\db\Expression('null')];
			}

			if( $reqBody['filters']['not_artikul1'] ){
				$cond1[] = ['is','artikul1',new \yii\db\Expression('null')];
				$cond2[] = ['is','artikul1',new \yii\db\Expression('null')];
				$cond3[] = ['is','artikul1',new \yii\db\Expression('null')];
				$cond4[] = ['is','artikul1',new \yii\db\Expression('null')];
				$cond5[] = ['is','artikul1',new \yii\db\Expression('null')];
			}

			

			
		
			$items = Product::find()
			  ->where($cond1)
			  ->orWhere($cond2)
			  ->orWhere($cond3)
			  ->orWhere($cond4)
			  ->with('page')
			  ->asArray()
			  ->orderBy(['UNIX_TIMESTAMP(updatedon)'=>SORT_DESC])
			  ->all();

			return ['items'=>$items];
		}

		if (Yii::$app->request->get('type') == 'createNew'){
			$item = new Product;

			$item->title = '';
			$item->h1 = '';
			$item->menuname = 'Пустой товар';
			$item->template = 5;

			if(!$item->save()) return $item->getErrors(); 

			return ['success'=>true,'id'=>$item->id];
		}

		if (Yii::$app->request->get('type') == 'savePredicate'){
			
			//if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
    			//$item = ServPredicate::findOne($reqBody['id']);
    			//if(!$item) return ['error'=>'ServPredicate not found'];
    		//}
    		//else 
    		$item = new ServPredicate;

			foreach(['name','description','image','price'] as $d){
				if($d == 'price'){
					$item->priceFixed = $reqBody[$d];
					continue;
				}
				$item->$d = $reqBody[$d];
			}

			if(!$item->save()) return $item->getErrors(); 

			return ['success'=>true,'items'=>servPredicate::find()->asArray()->all()];
		}
		
		if(Yii::$app->request->get('type') == 'getUrl'){
			
	   		if( array_key_exists('id',$reqBody) && $reqBody['id'] != 0){
				$item = Product::findOne($reqBody['id']);
				if(!$item) return ['error'=>'Product not found'];
			}
			
			if( !array_key_exists('title',$reqBody)  || !$reqBody['title']) return ['error'=>'Product not found'];
			
			return ['url'=>Helpers::translit($reqBody['title']).'-id'.$item->id];
			
		}

		if (Yii::$app->request->get('type') == 'getSimilar'){

    		$items = Product::find()->select(['id','h1','menuname','deleted'])->asArray()->all();
			
			$res = [];
			foreach($items as $i=>$d){
				if(!$d['menuname']) $d['menuname'] = $d['h1'];
				$res[] = ['name'=>$d['menuname'],'id'=>$d['id']];
			}
    		
    		return ['items'=>$res];
		}

		if (Yii::$app->request->get('type') == 'saveSort'){

    		foreach($reqBody['ids'] as $key=>$d){

				$link = PageProduct::find()->where(['productId'=>$d,'pageId'=>$reqBody['pageId']])->one();
				if($link){
					$link->sortId = $key;
					if(!$link->save()) return $link->getErrors();
				}
    			
			}

			return ['success'=>true];
		}

    	
	}
	
	private function pageUrl($name,$pageId=null){
		$url = '';
		if($pageId){
			$page = Page::find()->where(['id'=>$pageId])->asArray()->one();
			
			if( $page['parent'] ) {
				$url .= $page['parent']['url'].'/';
			}

			$url .= $page['url'].'/';
		}

		$url .= Helpers::translit($name).'/';
		
	}

}
