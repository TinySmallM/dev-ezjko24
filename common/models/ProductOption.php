<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_option".
 *
 * @property int $id
 * @property int $productId
 * @property float|null $price_sum
 * @property string|null $price_name
 * @property string|null $menuname
 * @property string|null $id_1c
 * @property int|null $published
 * @property int|null $deleted
 * @property string $updatedon
 * @property string|null $image
 */
class ProductOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId'], 'required'],
            [['productId', 'published', 'deleted'], 'integer'],
            [['price_sum'], 'number'],
            [['updatedon'], 'safe'],
            [['price_name'], 'string', 'max' => 25],
            [['menuname', 'id_1c', 'image'], 'string', 'max' => 255],
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
            'price_sum' => 'Price Sum',
            'price_name' => 'Price Name',
            'menuname' => 'Menuname',
            'id_1c' => 'Id 1c',
            'published' => 'Published',
            'deleted' => 'Deleted',
            'updatedon' => 'Updatedon',
            'image' => 'Image',
        ];
    }
}
