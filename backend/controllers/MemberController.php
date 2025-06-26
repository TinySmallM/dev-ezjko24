<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Helpers;
use common\models\Member;
use common\models\Order;
use common\models\MemberHistory;
use common\models\Coupon;
use common\models\MemberData;
use common\models\PlatformAccess;

class MemberController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','api','all'],
                        'roles' => ['member'],
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

    public function actionIndex(){
    	
    	return $this->render('index',[]);
        
    }

    public function actionAll(){
    	
    	return $this->render('all',[]);
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->get('type') == 'save'){
            $member = Member::find()->where(['token_hash'=>$reqBody['token_hash']])->one();
            if(!$member) return ['error'=>'Учетная запись не найдена'];

            if($member->email != $reqBody['email']){
                $me = Member::find()->where(['email'=>$reqBody['email']])->one();
                if($me) return ['error'=>'Email уже занят: '.$reqBody['email']];
            }

            if( isset($reqBody['vk_id']) && $member->vk_id != $reqBody['vk_id']){
                $me = Member::find()->where(['vk_id'=>$reqBody['vk_id']])->one();
                if($me) return ['error'=>'VK ID уже занят: '.$reqBody['vk_id']];
            }

            foreach(['firstname','lastname','patronymic','gender','birth_date','vk_id','email'] as $key){
                $member->$key = $reqBody[$key];
            }

            $member->save();

            return ['success'=>true];
        }

        if (Yii::$app->request->get('type') == 'getSearch'){
            $q = trim($reqBody['query']);


            $q_q = [];

            if($q){
                $q_q = 
                    ['OR',
                        ['like', 'username', $q],
                        ['like', 'firstname', $q],
                        ['like', 'lastname', $q],
                        ['like', 'phone', $q],
                        ['like', 'phone_2', $q],
                        ['like', 'platform_id', $q],
                        ['like', 'platform_profileId', $q],
                        ['like', 'email', $q],
                        ['like', 'platform_username', $q],
                        ['like', 'token_hash', $q],
                    ];
            }

            $items = Member::find()
                ->where($q_q)
                ->select(['token_hash','firstname','lastname','username','phone','email','vk_id','id','platform_id','birth_date','platform_profileId'])
                ->limit(40)
                ->orderBy(['id'=>SORT_DESC])
                ->asArray()->all();

            foreach($items as $i=>$d){
                $items[$i]['buys_count'] = Order::find()->where(['memberId'=>$d['id'],'status'=>3])->count();
                $items[$i]['buys_sum'] = Order::find()->where(['memberId'=>$d['id'],'status'=>3])->sum('amount');
            }

            return ['items'=>$items];


        }
    	
    	if (Yii::$app->request->get('type') == 'getByToken'){

            if( strlen($reqBody['token']) == 32){
                $member = Member::find()->where(['token_hash'=>$reqBody['token']])->one();
                if(!$member) return ['error'=>'Пациент не найден'];
            }
            else {
                $member = Member::findByToken($reqBody['token']);
                if(!$member) return ['error'=>'Пользователь не найден'];
            }

    		

            $member_arr = Member::find()
                ->where(['id'=>$member->id])
                ->with(['history'=>function($q){
                    $q->orderBy(['id'=>SORT_DESC]);
                }])
                ->asArray()->one();
            

            foreach($member_arr['history'] as $i=>$d){
                if(!$d['files']) $member_arr['history'][$i]['files'] = [];
                else $member_arr['history'][$i]['files'] = json_decode($d['files']);
            }

            $member_arr['order'] = Order::find()
            ->select(['id','memberId','amount','status','created','extPayDateCompleted','wpImported','platformCode','platformOtherEmail'])
            ->where(['memberId'=>$member_arr['id'],'status'=>[3]])
            ->with(['items'=>function($b){
                $b->with(['product']);
            }])->asArray()->all();

            $member_arr['coupon'] = Coupon::find()
                ->where(['stateId'=>1])
                ->andWhere(['OR', ['>','dateActiveUntil',date('Y-m-d H:i:s')] , ['is','dateActiveUntil',new \yii\db\Expression('null')] ] )
                ->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
                ->andWhere(['OR', ['<','dateCreated',date('Y-m-d H:i:s')] , ['is','dateCreated',new \yii\db\Expression('null')] ] )
                ->andWhere(['emailBind'=>$member_arr['email']])
                ->asArray()->all();

            $member_arr['platform_access'] = PlatformAccess::find()
                ->with(['page'=>function($q){
                    $q->select(['h1','id']);
                },'subject'])
                ->where(['memberId'=>$member_arr['id']])->asArray()->all();


            //$mtoe = MemberToEvent::find()->with(['event'])->where(['member_id'=>$m->id])->asArray()->all();

            $m_fields = Yii::$app->params['member_data'];
            foreach($m_fields as $i=>$d){
                $m['member_data__'.$i] = MemberData::find()->select(['value'])->where(['memberId'=>$member_arr['id'],'name'=>$i])->column()[0];
                
                if( !$m['member_data__'.$i] ) $m['member_data__'.$i] = null;

                /*if($m_fields[$i]['is_array']){
                    if( !$m_fields[$i]['user_value'] ) $m_fields[$i]['user_value'] = [];
                    else $m_fields[$i]['user_value'] = explode('|',$m_fields[$i]['user_value']);
                }*/
            }

            return $member_arr;

            
			
			//$item['chunk'] = Helpers::processChunk($item['chunk'],$item['template']);
    		
    		//return ['item'=>$item];
    	}

        if (Yii::$app->request->get('type') == 'historyAdd'){

    		$h = new MemberHistory;
            $h->memberId = $reqBody['memberId'];
            $h->text = $reqBody['text'];
            $h->action = $reqBody['type'];
            $h->files = json_encode($reqBody['files']);
            $h->adminId = Yii::$app->user->id;

            if(!$h->save()) return $h->getErrors();

            return $h->getAttributes();
    	}

        if (Yii::$app->request->get('type') == 'researchSave'){

    		$h = MemberResearch::findOne($reqBody['id']);
            $h->textResult = $reqBody['text'];
            $h->status = 'success';

            $h->save();

            return $h->getAttributes();
    	}
    	
    }

    private function removeDirectory($path) {
        // The preg_replace is necessary in order to traverse certain types of folder paths (such as /dir/[[dir2]]/dir3.abc#/)
        // The {,.}* with GLOB_BRACE is necessary to pull all hidden files (have to remove or get "Directory not empty" errors)
        $files = glob(preg_replace('/(\*|\?|\[)/', '[$1]', $path).'/{,.}*', GLOB_BRACE);
        foreach ($files as $file) {
            if ($file == $path.'/.' || $file == $path.'/..') { continue; } // skip special dir entries
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

    public function actionExport(){
        $members = Member::find()->asArray()->all();
        foreach($members as $i=>$m){
            $members['firstOrderDate'] = Order::find()->where(['memberId'=>$m['id']])->orderBy(['created'=>SORT_DESC])->asArray()->one();
            print_r($members);
            die();
        }
    }

}
