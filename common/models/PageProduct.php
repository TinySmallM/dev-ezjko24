<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_product".
 *
 * @property int $id
 * @property int|null $productId ID Товара
 * @property int $pageId ID страницы
 */
class PageProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'pageId'], 'integer'],
            [['pageId'], 'required'],
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
            'pageId' => 'Page ID',
        ];
    }
}
