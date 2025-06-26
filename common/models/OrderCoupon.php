<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_coupon".
 *
 * @property int $id
 * @property int $orderId
 * @property string $data
 * @property int $couponId
 * @property float $orderSumBefore
 * @property float $orderSumAfter
 * @property string $text
 */
class OrderCoupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orderId', 'couponId', 'orderSumBefore', 'orderSumAfter', 'text'], 'required'],
            [['orderId', 'couponId'], 'integer'],
            [['data', 'text'], 'string'],
            [['orderSumBefore', 'orderSumAfter'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Order ID',
            'data' => 'Data',
            'couponId' => 'Coupon ID',
            'orderSumBefore' => 'Order Sum Before',
            'orderSumAfter' => 'Order Sum After',
            'text' => 'Text',
        ];
    }
}
