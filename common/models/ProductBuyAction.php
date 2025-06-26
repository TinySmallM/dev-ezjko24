<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_buyaction".
 *
 * @property int $id
 * @property int $productId
 * @property string|null $itemName
 * @property int|null $itemId
 * @property string|null $itemAction
 */
class ProductBuyAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_buyaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId'], 'required'],
            [['productId', 'itemId'], 'integer'],
            [['itemName', 'itemAction'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Product ID',
            'itemName' => 'Item Name',
            'itemId' => 'Item ID',
            'itemAction' => 'Item Action',
        ];
    }
}
