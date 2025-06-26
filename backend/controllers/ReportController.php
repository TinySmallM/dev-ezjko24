<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Order;
use common\models\OrderItem;
use common\models\Page;
use common\models\PageProduct;
use common\models\Product;
use common\models\FingerprintAction;

class ReportController extends Controller
{
	
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['order','api'],
                        'roles' => ['report'],
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

	public function actionOrder(){
		return $this->render('order',[]);
	}
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'getOrderReport'){

            $reqBody['dateRange']['start'] = substr($reqBody['dateRange']['start'],0,10).' 00:00:00';
            $reqBody['dateRange']['end'] = substr($reqBody['dateRange']['end'],0,10).' 23:59:59';

            //return $reqBody['dateRange'];

            //$reqBody['dateRange']['start'] = date_create_from_format('U',strtotime($reqBody['dateRange']['start']))->format('Y-m-d 00:00:00');
            //$reqBody['dateRange']['end'] = date_create_from_format('U',strtotime($reqBody['dateRange']['end']))->format('Y-m-d 23:59:59');

            //if($reqBody['dateRange']['start'] == $reqBody['dateRange']['end']) $reqBody['dateRange']['end'] = date_create_from_format('U',strtotime($reqBody['dateRange']['start']))->format('Y-m-d 23:59:59');

            $likeWhere = [];
            if( !empty($reqBody['filter']['searchText']) ) $likeWhere = [
                'OR',
                ['LIKE','order.email',$reqBody['filter']['searchText']],
                ['LIKE','order.platformCode',$reqBody['filter']['searchText']],
                ['LIKE','order.hash',$reqBody['filter']['searchText']],
                ['LIKE','order.name',$reqBody['filter']['searchText']],
                ['LIKE','order.email',$reqBody['filter']['searchText']],
                ['LIKE','order.amount',$reqBody['filter']['searchText']],
                ['LIKE','order.phone',$reqBody['filter']['searchText']],
                ['LIKE','order.extPayId',$reqBody['filter']['searchText']],
                ['LIKE','order.comment',$reqBody['filter']['searchText']],
                ['LIKE','order_coupon.text',$reqBody['filter']['searchText']],
                ['LIKE','order_item.name',$reqBody['filter']['searchText']],
                ['LIKE','order_item.product_id',$reqBody['filter']['searchText']],
            ];

            $condWhere = ['AND'];
            if( isset($reqBody['filter']['includeOrderStatus']) && $reqBody['filter']['includeOrderStatus'] ){
                $condWhere[] = ['order.status'=>$reqBody['filter']['includeOrderStatus']];
            }
            if( isset($reqBody['filter']['includeProductIds']) && $reqBody['filter']['includeProductIds'] ){
                $condWhere[] = ['order_item.product_id'=>$reqBody['filter']['includeProductIds']];
            }
            if( isset($reqBody['filter']['includePayMethod']) && $reqBody['filter']['includePayMethod'] ){
                $condWhere[] = ['order.payMethod'=>$reqBody['filter']['includePayMethod']];
            }

            if( isset($reqBody['filter']['member_stats__orderCompletedSumTotal_min']) && $reqBody['filter']['member_stats__orderCompletedSumTotal_min'] ){
                $condWhere[] = ['>','member_stats.orderCompletedSumTotal',$reqBody['filter']['member_stats__orderCompletedSumTotal_min']];
            }

            if( isset($reqBody['filter']['member_stats__orderCompletedSumTotal_max']) && $reqBody['filter']['member_stats__orderCompletedSumTotal_max'] ){
                $condWhere[] = ['<','member_stats.orderCompletedSumTotal',$reqBody['filter']['member_stats__orderCompletedSumTotal_max']];
            }

            if( isset($reqBody['filter']['member_stats__orderCompletedCountTotal_min']) && $reqBody['filter']['member_stats__orderCompletedCountTotal_min'] ){
                $condWhere[] = ['>','member_stats.orderCompletedCountTotal',$reqBody['filter']['member_stats__orderCompletedCountTotal_min']];
            }

            if( isset($reqBody['filter']['member_stats__orderCompletedCountTotal_max']) && $reqBody['filter']['member_stats__orderCompletedCountTotal_max'] ){
                $condWhere[] = ['<','member_stats.orderCompletedCountTotal',$reqBody['filter']['member_stats__orderCompletedCountTotal_max']];
            }

            if( isset($reqBody['filter']['order_isFirstForMember']) && $reqBody['filter']['order_isFirstForMember'] ){
                if($reqBody['filter']['order_isFirstForMember'] == 'first') $condWhere[] = ['order.isFirstForMember'=>1];
                else if($reqBody['filter']['order_isFirstForMember'] == 'not-first') $condWhere[] = ['is', 'order.isFirstForMember', new \yii\db\Expression('null')];
            }

            


            if( isset($reqBody['filter']['includePageIds']) && $reqBody['filter']['includePageIds'] ){

                $ids_child = Page::find()->where(['parent'=>$reqBody['filter']['includePageIds']])->select(['id'])->column();
                $ids_merged = array_merge($ids_child,$reqBody['filter']['includePageIds']);

                $ids = PageProduct::find()->where(['pageId'=>$ids_merged])->select(['productId'])->column();

                $condWhere[] = ['order_item.product_id'=>$ids];
            }

            if( isset($reqBody['filter']['visitParams']) && $reqBody['filter']['visitParams'] ){
                $fingerprintId_first = FingerprintAction::find()
                    ->select(['distinct(fingerprintId)'])    
                    ->where(['LIKE','params',$reqBody['filter']['visitParams']])
                    //->andWhere(['between','dateCreated',$reqBody['dateRange']['start'],$reqBody['dateRange']['end']])
                    ->column();
                $condWhere[] = ['order.fingerprintId'=>$fingerprintId_first];

                //return $condWhere;
            }

    		$item = [
				'orderItem'=>OrderItem::find()
                    ->leftJoin('order','order_item.order_id = order.id')
                    ->leftJoin('order_coupon','order.id = order_coupon.orderId')
                    ->leftJoin('fingerprint_action','order.fingerprintFirstActionId = fingerprint_action.id')
                    ->leftJoin('fingerprint','fingerprint_action.fingerprintId = fingerprint.id')
                    ->leftJoin('member','order.memberId = member.id')
                    ->leftJoin('member_stats','member_stats.memberId = member.id')
                    ->where($likeWhere)
					->andWhere(['between','order.created',$reqBody['dateRange']['start'],$reqBody['dateRange']['end']])
                    ->andWhere($condWhere)
                    ->select([
                        'order_item.product_id','order_item.name','order_item.priceSum','order_item.coupon_priceSum','order_item.priceName','order_item.priceType','order_item.quantity',
                        'order.id AS order_id','order.name AS order_name','order.amount AS order_amount','order.created AS order_created','order.status AS order_status',
                        'order.email AS order_email','order_coupon.text AS coupon_text',
                        'order.payMethod AS order_payMethod',
                        'order.platformCode AS order_platform_code',
                        'order.isFirstForMember AS order_isFirstForMember',
                        'member.id AS member_id',
                        'member.email AS member_email',
                        'member.firstname AS member_firstname',
                        'order_coupon.orderSumBefore AS coupon_orderSumBefore',
                        'order_coupon.orderSumAfter AS coupon_orderSumAfter',
                        'fingerprint_action.referer AS first_fingerprint_action_referer',
                        'fingerprint_action.params AS first_fingerprint_action_params',
                        'fingerprint_action.url AS first_fingerprint_action_url',
                        'fingerprint.os_backend AS fingerprint__os_backend',
                        'fingerprint.device_type_backend AS fingerprint__device_type_backend',
                        'fingerprint.timezone_frontend AS fingerprint__timezone_frontend',
                        'fingerprint.display_frontend AS fingerprint__display_frontend',
                        'member_stats.orderCompletedSumTotal AS member_stats__orderCompletedSumTotal',
                        'member_stats.orderCompletedCountTotal AS member_stats__orderCompletedCountTotal',
                        'member_stats.firstOrderDate AS member_stats__firstOrderDate',
                        'member_stats.lastOrderDate AS member_stats__lastOrderDate',
                        ])
                    ->orderBy('order_item.id ASC')
                    ->asArray()->all()
			];

            if( isset($reqBody['includePageAll']) ){
                $item['pageAll'] = Page::find()->asArray()->all();
            }

            if( isset($reqBody['includeProductAll']) ){
                $item['productAll'] = Product::find()->asArray()->all();
            }
    		
    		return $item;
    	}
    	
    }

}
