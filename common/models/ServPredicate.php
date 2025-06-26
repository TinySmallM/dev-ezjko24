<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "servPredicate".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $group
 * @property string $image
 * @property float|null $priceCalc
 * @property int|null $priceFixed
 */
class ServPredicate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servPredicate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['priceCalc','priceFixed'], 'number'],
            [['name', 'image'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 1200],
            [['group'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Image',
            'priceCalc' => 'Price Calc',
            'priceFixed' => 'Price Fixed',
            'group' => 'Group'
        ];
    }
}
