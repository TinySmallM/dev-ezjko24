<?php

namespace common\models;

use Yii;
use common\models\Coupon;
use common\models\Member;
use common\models\OrderItem;
use common\models\TelegramApi;

/**
 * This is the model class for table "order".
 *
 * @property int $id Идентификатор заказа
 * @property int $memberId Идентификатор пользователя
 * @property string $name Имя и фамилия покупателя
 * @property string $email Почта покупателя
 * @property string $phone Телефон покупателя
 * @property string $comment Комментарий к заказу
 * @property float $amount Сумма заказа
 * @property int $orgType Статус клиента
 * @property int $payMethod Способ оплаты
 * @property int $delivery Способ доставки
 * @property int $status Статус заказа
 * @property string $created Дата и время создания
 * @property string $updated Дата и время обновления
 * @property string|null $hash
 * @property string|null $extPayUrl
 * @property string|null $extPayId
 * @property int|null $extPayStatus
 * @property string $extPayDateCompleted Дата и время платежа
 * @property string|null $platformCode
 * @property string|null $platformOtherEmail
 * @property int|null $cancelledByOrderId
 * @property int|null $wpImported
 * @property int|null $completedOrdersCount
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberId', 'orgType', 'payMethod', 'delivery', 'status', 'extPayStatus','cancelledByOrderId','wpImported','isFirstForMember'], 'integer'],
            [['amount'], 'number'],
            [['payMethod'], 'required'],
            [['created', 'updated', 'extPayDateCompleted'], 'safe'],
            [['extPayUrl'], 'string', 'max'=>1000],
            [['extPayId'], 'string', 'max'=>48],
            [['name', 'email', 'phone'], 'string', 'max' => 50],
            [['comment', 'hash','platformCode','platformOtherEmail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memberId' => 'Member ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'comment' => 'Comment',
            'amount' => 'Amount',
            'orgType' => 'Org Type',
            'payMethod' => 'Pay Method',
            'delivery' => 'Delivery',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
            'hash' => 'Hash',
            'extPayUrl' => 'Ext Pay Url',
            'extPayId' => 'Ext Pay Id',
            'extPayStatus' => 'Ext Pay Status',
            'extPayDateCompleted' => 'Ext Pay Date Completed',
            'platformCode' => 'PlatformCode',
            'isNewMember' => 'Is New Member'
        ];
    }
    
    public function afterSave($insert, $changedAttributes){

	    parent::afterSave($insert, $changedAttributes);

        if($insert){
            exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii mail/order '.$this->id.' new > /dev/null 2>/dev/null &');
        }

	    if($changedAttributes && !$this->wpImported){

            
            if(!$this->memberId){
                $m = Member::createOrGetByEmail($this->email,$this->name?$this->name:null,$this->phone?$this->phone:null);
                if($m){
                    $this->memberId = $m->id;
                    $this->save();
                }
            }
            //
	    	
			if(isset($changedAttributes['amount']) && $changedAttributes['amount'] != $this->amount){
	    		$this->extPayId = null;
	    		$this->extPayUrl = null;
	    		$this->save();
	    	}
	    	
	    	if( isset($changedAttributes['status']) ){
	    		exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii mail/order '.$this->id.' status > /dev/null 2>/dev/null &');

                if($this->status == 3){

                    //Order::sendTgMessage($this->id);

                    //access to new platform
                    //exec('/opt/php74/bin/php '.Yii::$app->basePath.'/../yii platform/access-by-order '.$this->id.' > /dev/null 2>/dev/null &');
                    
                    //Send personal coupon
                    /*if($this->amount > 10000){
                        Coupon::addPersonal(25,'percent','+7 days','auto_after_buy_10000',$this->email,true);
                    }
                    else if($this->amount > 5000){
                        Coupon::addPersonal(15,'percent','+7 days','auto_after_buy_5000',$this->email,true);
                    }
                    else if($this->amount > 1000){
                        Coupon::addPersonal(10,'percent','+7 days','auto_after_buy_1000',$this->email,true);
                    }
                    */
                }
                
	    	}
	    	
	    }

	}
    
    public function getitems(){
		return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
	}
	
	public function getfingerprint(){
		return $this->hasOne(Fingerprint::className(), ['id' => 'fingerprintId']);
	}

    public function getdolyame(){
		return $this->hasOne(OrderDolyame::className(), ['order_id' => 'id']);
	}

    public static function sendTgMessage($id){
        $order = Order::findOne($id);
        $oi = OrderItem::find()->where(['order_id'=>$id])->asArray()->all();
        //сообщение о покупке в телегу
        $msg = '================='.PHP_EOL.'Заказ <b>#'.$order->id.'</b> на сумму <b>'.$order->amount.' руб</b>'.PHP_EOL.'================='.PHP_EOL;
        $msg .= '|  Email: '.$order->email.PHP_EOL;
        $msg .= '|  Телефон: '.$order->phone.PHP_EOL;

        $msg .= '|'.PHP_EOL;

        $msg .= '|  Корзина:'.PHP_EOL;

        foreach($oi as $i=>$d){
            $msg .= '|  – x'.$d['quantity'].' '.$d['name']. ' за '.($d['coupon_priceSum']?$d['coupon_priceSum'].' руб/шт (промокод)':$d['priceSum'].' руб/шт').PHP_EOL;
        }

        $msg .= '|'.PHP_EOL;

        $msg .= '|  <a href="https://rus-electronika.ru/master/order/'.$order->id.'">Открыть в админке</a>'.PHP_EOL;

        $msg  .= '=================';

        
        $TelegramApi = new TelegramApi();
        $TelegramApi->user_id = 270089019;
        $TelegramApi->text = $msg;
        $TelegramApi->sendMessage();

        $TelegramApi = new TelegramApi();
        $TelegramApi->user_id = 5269798371; // abbas ziya
        $TelegramApi->text = $msg;
        $TelegramApi->sendMessage();

        

        
        /*
        $TelegramApi = new TelegramApi();
        $TelegramApi->user_id = 5929221543;
        $TelegramApi->text = $msg;
        $TelegramApi->sendMessage();
        */

        

    } 
	
}
