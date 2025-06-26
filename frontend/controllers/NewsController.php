<?php
namespace frontend\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\News;
use common\models\Helpers;
/**
 * Site controller
 */
class NewsController extends Controller {
    
    /*
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'all' => ['get']
                ],
            ],
        ];
    }
    */
    public function beforeAction($action){
    	Helpers::currentRegion();
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
    
    /* One */
   	public function actionIndex($id){
        
        $item = News::find()->where(['isPublished'=>1,'isDeleted'=>null,'id'=>$id])->asArray()->one();
		
    	//Если не нашли - 404
    	if(empty($item)) throw new HttpException(404 ,'Страница не найдена');
    	
    	$this->layout = 'main';
		return $this->render('index',['item'=>$item]);
	}
    
    
	public function actionAll(){
		$items = News::find()->where(['isPublished'=>1,'isDeleted'=>null])->orderBy(['dateCreated'=>SORT_DESC])->asArray()->all();
		
		$this->layout = 'main';
		return $this->render('all',['items'=>$items]);
	}



}
