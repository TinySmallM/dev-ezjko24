<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "productImage".
 *
 * @property int $id
 * @property int $productId
 * @property string $image
 * @property int|null $orderId
 * @property string|null $description
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productImage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'image'], 'required'],
            [['productId', 'orderId'], 'integer'],
            [['image', 'description'], 'string', 'max' => 255],
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
            'image' => 'Image',
            'orderId' => 'Order Id',
            'description' => 'Description',
        ];
    }
}
