<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Helpers;
use common\models\Tags;
use common\models\TagsPreset;
use common\models\Member;


class TagsController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','api','all'],
                        'roles' => ['admin'],
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

        $rb = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->get('type') == 'presetsGetAll'){
            //$w = [];
            //if($rb['where']) $w = $rb['where'];

            $items = TagsPreset::find()->where(['itemType'=>$rb['itemType']])->asArray()->all();

            return ['items'=>$items];
        }

        if (Yii::$app->request->get('type') == 'presetsAdd'){

            $tag = TagsPreset::find()->where(['itemType'=>$rb['itemType'],'tag'=>$rb['tag']])->one();
            if(!$tag){
                $tag = new TagsPreset;
                $tag->itemType = $rb['itemType'];
                $tag->tag = $rb['tag'];
                if(!$tag->save()) return $tag->getErrors();
            }

            return ['success'=>true,'item'=>$tag->getAttributes()];

        }

        if (Yii::$app->request->get('type') == 'saveForItem'){

            if($rb['itemType'] == 'member'){
                $member = Member::find()
                    ->where(['id'=>$rb['itemId']])
                ->one();
                if(!$member) return ['error'=>'Учетная запись не найдена'];
            }

            $tags = Tags::find()->where(['itemId'=>$rb['itemId'],'itemType'=>$rb['itemType']])->one();
            if( !$tags ){
                $tags = new Tags;
                $tags->itemId = $rb['itemId'];
                $tags->itemType = $rb['itemType'];
                if(!$tags->save()) return $tags->getErrors();
            }
            $tags->tags = implode('|',$rb['tags']);
            if(!$tags->save()) return $tags->getErrors();

            return ['success'=>true,'tags'=>$tags->tags];
        }    	
    }

}
