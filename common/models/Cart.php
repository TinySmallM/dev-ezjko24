<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property string $hash
 * @property string|null $cart_arr
 * @property string|null $coupon_text
 * @property string|null $coupon_arr
 * @property string $dateCreated
 * @property string|null $createdBy
 * @property string|null $dateActiveUntil
 * @property int|null $stateId
 * @property string|null $description
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hash'], 'required'],
            [['cart_arr', 'coupon_arr', 'createdBy', 'description'], 'string'],
            [['dateCreated', 'dateActiveUntil'], 'safe'],
            [['stateId'], 'integer'],
            [['hash'], 'string', 'max' => 32],
            [['coupon_text'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'cart_arr' => 'Cart Arr',
            'coupon_text' => 'Coupon Text',
            'coupon_arr' => 'Coupon Arr',
            'dateCreated' => 'Date Created',
            'createdBy' => 'Created By',
            'dateActiveUntil' => 'Date Active Until',
            'stateId' => 'State ID',
            'description' => 'Description',
        ];
    }
}
