<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id Идентификатор элемента
 * @property int $order_id Идентификатор заказа
 * @property int $product_id Идентификатор товара
 * @property string $name Наименование товара
 * @property int $quantity Количество в заказе
 * @property float $cost Стоимость = Цена * Кол-во
 * @property string|null $priceName
 * @property float $priceSum Цена товара
 * @property float $coupon_priceSum Цена товара с промокодом
 * @property int $priceType Цена товара
 * @property int|null $nds
 * @property int|null $artikul
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id'], 'required'],
            [['order_id', 'product_id', 'quantity', 'nds','priceType','artikul'], 'integer'],
            [['priceSum', 'cost','coupon_priceSum'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['priceName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'cost' => 'Cost',
            'measure' => 'Measure',
            'nds' => 'Nds',
            'priceType'=>'Price Type',
            'coupon_priceSum'=>'Coupon Price Sum',
            'artikul'=>'Artikul'
        ];
    }

    public function getproduct(){
		return $this->hasOne(Product::className(), ['id' => 'product_id']);
	}
}
