<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int|null $productId
 * @property string $fio
 * @property string|null $doc
 * @property string $text
 * @property int $stars
 * @property string $image
 * @property int $dateCreated
 * @property int|null $isPublished
 * @property int|null $regionId
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'stars', 'dateCreated', 'isPublished','regionId'], 'integer'],
            [['fio', 'text', 'stars', /*'image',*/ 'dateCreated'], 'required'],
            [['fio'], 'string', 'max' => 50],
            [['doc'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 2500],
            [['image'], 'string', 'max' => 1000],
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
            'fio' => 'Fio',
            'doc' => 'Doc',
            'text' => 'Text',
            'stars' => 'Stars',
            'image' => 'Image',
            'dateCreated' => 'Date Created',
            'isPublished' => 'Is Published',
            'regionId' => 'Region Id',
        ];
    }

    public function getproduct(){
		return $this->hasOne(Product::className(), ['id' => 'productId']);
	}
}
