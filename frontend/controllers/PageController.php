<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Page;
use common\models\Product;
use common\models\Chunk;
use common\models\Helpers;
use common\models\ProductImage;
use common\models\PageProduct;
use common\models\Review;
use common\models\User;
use frontend\controllers\FingerprintController;

use common\models\CharactsFields;
use common\models\CharactsFieldsValues;

//use backend\models\Chunkks;
/**
 * Site controller
 */
class PageController extends Controller {
    
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }
    public function beforeAction($action){
    	
    	FingerprintController::collectBackend();
        
    	Helpers::currentRegion();
    	
    	//Yii::$app->params['chunk_global'] = Blocks::find()->asArray()->all();
        //Yii::$app->params['chunk_global'] = ArrayHelper::index(Yii::$app->params['chunk_global'],'name');
        
		return parent::beforeAction($action);
	}


	/* Page render */
    public function actionIndex($url = 'index'){
    	
    	session_start();
    	if( empty($_SESSION['cart']) ) $_SESSION['cart'] = [];
    	
    	if(!$url) $url = 'index';
        
        //Получаем страницу, данные
    	$where = ['url'=>$url];
    	$whereUnpublished = ['published'=>1];
    	if( User::isAdmin() ) $whereUnpublished = [];
		

        //Категория, если ее нет - товар
    	$data = Page::find()
    		->with(['child' => function ($q){
    			$q->select(['id','description','parent','url','menuname','image','title','template','parent'])->where($whereUnpublished)->with(['chunk','product'=>function($b){
    				$b->orderBy(['price1_sum'=>SORT_ASC])->select(['id','h1','price1_sum','price2_sum','image'])->where(['!=','price1_sum',0])->one();
    			}])->orderBy(['sortId'=>SORT_ASC]);
    		},'chunk','charactsfields'])->where($where)->asArray()->one();

    
    	
		if($data){

      foreach($data['charactsfields'] as $i=>$d){
				$data['charactsfields'][$i]['options'] = explode('|',$d['options']);
			}

      $orderBy = [];
      $pp_w = ['AND'];

      // сортировка
      if( Yii::$app->request->get('sort') && is_array(Yii::$app->request->get('sort')) ){
        foreach(Yii::$app->request->get('sort') as $f_key=>$f_val){
          $orderBy[$f_key] = $f_val=='asc'?SORT_ASC:SORT_DESC;
        }
      }

      // фильтрация
      if( Yii::$app->request->get('filters') && is_array(Yii::$app->request->get('filters')) ){
        
        foreach(Yii::$app->request->get('filters') as $f_key=>$f_val){
          if( isset($f_val['from']) ){
            $pp_w[] = ['>=',$f_key,$f_val['from']];
          }
          if( isset($f_val['to']) ){
            $pp_w[] = ['<=',$f_key,$f_val['to']];
          }
          if( isset($f_val['equals']) ){
            $pp_w[] = ['=',$f_key,$f_val['equals']];
          }
        }
      }

      if( Yii::$app->request->get('ch') && is_array(Yii::$app->request->get('ch')) ){
        foreach(Yii::$app->request->get('ch') as $f_key=>$f_val){
          $pp_w[] = ['LIKE','characts',$f_key.'=>'.$f_val];
        }
      }

      if(!$orderBy) $oderBy = ['pp.sortId'=>SORT_ASC];

      if(!$data['image'] && $data['child']){

        foreach($data['child'] as $i=>$d){
          if($data['image']) continue;
          if($d['image']) $data['image'] = $d['image'];
          
          foreach($d['product'] as $i_p=>$d_p){
            if($data['image']) continue;
            if($d_p['image']) $data['image'] = $d_p['image'];
          }
        }

      }


      //print_r(Yii::$app->request->get('filters'));
      //die();


			$data['product'] = Product::find()
				->select(['product.id','url','menuname','price1_sum','price2_sum','price1_name','price2_name','image','title','template','description','content','h1'])
				->innerJoin('page_product pp', 'pp.productId = product.id ')
				->where(['menushow'=>1,'pp.pageId'=>$data['id']])
        ->andWhere($pp_w)
				->with(['chunk','gallery'])
				->orderBy($orderBy)
				->asArray()->all();
		}

    
		
    	if(!$data) {
        $data = Product::find()->with(['chunk','gallery','charactsfieldsvalues','page'=>function($q){
    			$q->select(['id'])->column();
    		}])->where($where)->andWhere($whereUnpublished)->asArray()->one();

        if($data){
          $data['page'] = ArrayHelper::getColumn($data['page'], 'id');
          $data['charactsfieldsvalues'] = ArrayHelper::index($data['charactsfieldsvalues'],'fieldId');

          $data['charactsfields'] = CharactsFields::find()->where(['pageId'=>$data['page']])->asArray()->all();

          foreach($data['charactsfields'] as $i=>$d){

            if( isset($data['charactsfieldsvalues'][$d['id']]) && $data['charactsfieldsvalues'][$d['id']]['data']) $data['charactsfields'][$i]['value'] = $data['charactsfieldsvalues'][$d['id']]['data'];
            else if( $data['charactsfields'][$i]['defaultVal'] ) $data['charactsfields'][$i]['value'] = $data['charactsfields'][$i]['defaultVal'];
            else $data['charactsfields'][$i]['value'] = 'Не указано';
          }

          $data['charactsfields_groups'] = ArrayHelper::getColumn($data['charactsfields'],'groupName');
          $data['charactsfields_groups'] = array_unique($data['charactsfields_groups']);

        }

      }
    	
    	//Если не нашли - 404
    	if(empty($data)) throw new HttpException(404 ,'Страница не найдена');
    	
		if( $data['published'] != 1 && !User::isAdmin() ) throw new HttpException(404 ,'Страница не найдена');
		
		//echo(json_encode($data));
		
		//Обрабатываем чанки
		$data['chunk'] = Helpers::processChunk($data['chunk'],$data['template']);
		if(isset($data['child'])){
			foreach($data['child'] as $i=>$d){ //Для потомков - тоже
				$data['child'][$i]['chunk'] = Helpers::processChunk($d['chunk'], $d['template']);
			}			
		}
		if(isset($data['product'])){
			foreach($data['product'] as $i=>$d){ //Для товаров - тоже
				$data['product'][$i]['chunk'] = Helpers::processChunk($d['chunk'], $d['template']);
			}
		}
		
		//Запоминаем последнюю страницу
		/*
		Yii::$app->session->open();
		//$_SESSION['product'] = [];
		if(empty($_SESSION['category'])) $_SESSION['category'] = [];
		if(empty($_SESSION['product'])) $_SESSION['product'] = [];

		if( array_key_exists('artikul',$data) ) {
			if( array_key_exists($data['id'], $_SESSION['product']) ) unset($_SESSION['product'][$data['id']]);
			$_SESSION['product'][$data['id']] = $data;
		}
		else $_SESSION['category'][] = ['id'=>$data['id'],'url'=>$data['url'],'menuname'=>$data['menuname'],'chunk'=>$data['chunk']];
		
		if(count($_SESSION['category']) > 10) array_pop($_SESSION['category']);
		if(count($_SESSION['product']) > 10) array_pop($_SESSION['product']);
*/
		//print_r($data);

    if(!$data['description']){

      if( isset($data['chunk']['text1']) ){
        $data['description'] = $data['chunk']['text1']['content'];
      }
      else if( isset($data['chunk']['text2']) ){
        $data['description'] = $data['chunk']['text2']['content'];
      }

      $data['description'] = strip_tags($data['description']);

    }
		
		//Рендерим страницу
		$tpl = Yii::$app->params['template'];
		
	    
	    if(array_key_exists($data['template'], $tpl)){

			if($tpl[$data['template']]['file'] == 'clear'){
				$this->layout = false;
				return $this->renderPartial('clear',['data'=>$data]);
			}

			$this->layout = 'main';

	    	return $this->render($tpl[$data['template']]['file'],['data'=>$data]);
	    } 
		else throw new HttpException(502,'Шаблон не установлен');
	}

	public function actionSearch(){
		$str = Yii::$app->request->get('text');

    $cond1 = ['AND'];
    $cond2 = ['AND'];
    $cond3 = ['AND'];
    $cond4 = ['AND'];
    foreach(explode(' ',$str) as $s){
      $cond1[] = ['LIKE','content',$s];
      $cond2[] = ['LIKE','title',$s];
      $cond3[] = ['LIKE','h1',$s];
      $cond4[] = ['LIKE','url',$s];
    }

		$items = Product::find()
      ->where($cond1)
      ->orWhere($cond2)
      ->orWhere($cond3)
      ->orWhere($cond4)
			->andWhere(['published'=>1])
			//->with(['chunk'])
			->asArray()->all();

		//return json_encode($projects);

		return $this->render('search',['items'=>$items,'data'=>[
			'title'=>'Поиск по сайту',
			'description'=>'Поиск по сайту',
			'image'=>''
		]]);


	}

  public function actionVideo(){
    return $this->render('video',[]);
  }

	
	public function actionReview(){
		$data = [
			'title'=>'Наши отзывы',
			'description'=>'Актуальные отзывы о школе ChristMedSchool. Добавьте новый, чтобы рассказать о своем опыте обучения.'
		];

		$data['items'] = Review::find()->where(['isPublished'=>1])
		->orderBy(['dateCreated'=>SORT_DESC])->asArray()->all();
		/*foreach($data['items'] as $i=>$d){ //Картинки в массив...
			$data['items'][$i]['image'] = json_decode($d['image'],1);
		}*/

    foreach($data['items'] as $i=>$d){
      $data['items'][$i]['image'] = json_decode($d['image'],1);
    }

		$this->layout = 'main';
		return $this->render('review',['data'=>$data]);
	}
	

	public function actionApi(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 $reqBody = Yii::$app->request->post();

		if( Yii::$app->request->get('type') == 'product_content' ){
			$data = Product::find()->where(['id'=>Yii::$app->request->get('id')])->asArray()->one();

			if(!$data) return ['error'=>1,'msg'=>'no data'];

			$data['content'] = '<h1 style="font-weight: 600;">Бокс «'.$data['title'].'»</h1>'.$data['content'];

			//$data['chunk'] = Helpers::processChunk($data['chunk'],$data['template']);

      FingerprintController::addAction('product_content',$data['id']);

			return ['data'=>$data['content']];
		}

    if( Yii::$app->request->get('type') == 'page_in_modal' ){
      $data = Page::find()->where(['id'=>Yii::$app->request->get('id')])->one();
      if($data){
        return ['data'=>$data->content];
      }
      else return null;
    }

    if( Yii::$app->request->get('type') == 'lesson_example' ){
			$data = Chunk::find()->where(['itemId'=>Yii::$app->request->get('id'),'itemType'=>1,'name'=>'lesson_example'])->asArray()->one();

			if(!$data) return ['error'=>1,'msg'=>'no data'];

			$data['content'] = '<div class="platform_format">'.$data['content'].'</div>';

			//$data['chunk'] = Helpers::processChunk($data['chunk'],$data['template']);

      FingerprintController::addAction('lesson_example|'.$data['itemId'],);

			return ['data'=>$data['content']];
		}

		if( Yii::$app->request->get('type') == 'product_by_course' ){
	$pr = [
  0 => [
    'product_id' => 63,
    'when' => '1 курс 1 семестр',
  ],
  1 => [
    'product_id' => 66,
    'when' => '1 курс 1 семестр',
  ],
  2 => [
    'product_id' => 71,
    'when' => '1 курс 1 семестр',
  ],
  3 => [
    'product_id' => 77,
    'when' => '1 курс 1 семестр|1 курс 2 семестр',
  ],
  4 => [
    'product_id' => 82,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  5 => [
    'product_id' => 87,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  6 => [
    'product_id' => 93,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  7 => [
    'product_id' => 98,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  8 => [
    'product_id' => 106,
    'when' => '1 курс 1 семестр|1 курс 2 семестр',
  ],
  9 => [
    'product_id' => 114,
    'when' => '3 курс 1 семестр',
  ],
  10 => [
    'product_id' => 122,
    'when' => '3 курс 1 семестр',
  ],
  11 => [
    'product_id' => 129,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  12 => [
    'product_id' => 133,
    'when' => '3 курс 1 семестр',
  ],
  13 => [
    'product_id' => 139,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  14 => [
    'product_id' => 143,
    'when' => '4 курс 1 семестр|4 курс 2 семестр',
  ],
  15 => [
    'product_id' => 159,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  16 => [
    'product_id' => 160,
    'when' => '1 курс 2 семестр',
  ],
  17 => [
    'product_id' => 161,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  18 => [
    'product_id' => 162,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  19 => [
    'product_id' => 163,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  20 => [
    'product_id' => 164,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  21 => [
    'product_id' => 165,
    'when' => '1 курс 2 семестр|2 курс 1 семестр|2 курс 2 семестр',
  ],
  22 => [
    'product_id' => 166,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  23 => [
    'product_id' => 167,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  24 => [
    'product_id' => 168,
    'when' => '3 курс 2 семестр',
  ],
  25 => [
    'product_id' => 170,
    'when' => '3 курс 2 семестр',
  ],
  26 => [
    'product_id' => 175,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  27 => [
    'product_id' => 181,
    'when' => '1 курс 2 семестр|2 курс 1 семестр|2 курс 2 семестр',
  ],
  28 => [
    'product_id' => 182,
    'when' => '1 курс 2 семестр|2 курс 1 семестр|2 курс 2 семестр',
  ],
  29 => [
    'product_id' => 183,
    'when' => '1 курс 1 семестр|1 курс 2 семестр|2 курс 1 семестр|2 курс 2 семестр|3 курс 1 семестр|3 курс 2 семестр|4 курс 1 семестр',
  ],
  30 => [
    'product_id' => 184,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  31 => [
    'product_id' => 185,
    'when' => '1 курс 1 семестр',
  ],
  32 => [
    'product_id' => 186,
    'when' => '1 курс 2 семестр|2 курс 1 семестр|2 курс 2 семестр',
  ],
  33 => [
    'product_id' => 188,
    'when' => '3 курс 1 семестр',
  ],
  34 => [
    'product_id' => 189,
    'when' => '3 курс 1 семестр',
  ],
  35 => [
    'product_id' => 190,
    'when' => '3 курс 1 семестр',
  ],
  36 => [
    'product_id' => 193,
    'when' => '1 курс 2 семестр|2 курс 1 семестр',
  ],
  37 => [
    'product_id' => 199,
    'when' => '3 курс 1 семестр|3 курс 2 семестр',
  ],
  38 => [
    'product_id' => 200,
    'when' => '2 курс 1 семестр|2 курс 2 семестр',
  ],
  39 => [
    'product_id' => 211,
    'when' => '3 курс 2 семестр',
  ],
  40 => [
    'product_id' => 222,
    'when' => '1 курс 1 семестр|1 курс 2 семестр',
  ],
  41 => [
    'product_id' => 235,
    'when' => '3 курс 2 семестр',
  ],
];

			$rec_ids = [];
			foreach($pr as $p){
				if( strpos($p['when'],$reqBody['when']) > -1 ) $rec_ids[] = $p['product_id'];
			}

			$pr_arr = Product::find()->select(['id','title','image','price1_sum','price1_name','price2_sum','price2_name'])->where(['id'=>$rec_ids])->asArray()->all();

      foreach($pr_arr as $i=>$d){
        $p_p = PageProduct::find()->where(['productId'=>$d['id']])->one();
        if($p_p) $pr_arr[$i]['category_url'] = Page::find()->where(['id'=>$p_p['pageId']])->select(['url'])->column()[0];
      }

      FingerprintController::addAction('product_by_course|'.$reqBody['when']);

			return $pr_arr;
		}
	}
	
	/* Sitemap */
    public function actionSitemap(){
    	Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    	Yii::$app->response->headers->add('Content-Type', 'text/xml');
    	$pages = Page::find()->where(['published'=>1,'deleted'=>null])->orderBy(['id'=>SORT_ASC])->all();
    	$product = Product::find()->where(['published'=>1,'deleted'=>null])->orderBy(['id'=>SORT_ASC])->all();
    	return $this->renderPartial('sitemap',['page'=>$pages,'product'=>$product]);
    }

	public function actionProducts(){
    	Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    	Yii::$app->response->headers->add('Content-Type', 'text/xml');
    	
		$pages = Page::find()
			->where(['published'=>1,'deleted'=>null])
			->with(['product'=>function($m){
        //$m->with(['characts']);
      }])
			->orderBy(['id'=>SORT_ASC])
			->asArray()->all();

    	return $this->renderPartial('products',['page'=>$pages]);
    }
    
    public function actionRobots(){
    	return $this->renderPartial('robots');
    }
    
    public function actionRedirect($url = ''){
        if (substr($url, -1) == '/') {
            return $this->redirect('/' . substr($url, 0, -1));
        } else {
            throw new NotFoundHttpException;
        }
    }

}