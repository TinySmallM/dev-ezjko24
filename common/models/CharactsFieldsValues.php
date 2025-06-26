<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "charactsFieldsValues".
 *
 * @property int $id
 * @property int|null $productId
 * @property int $fieldId
 * @property string $fieldName
 * @property string|null $data
 */
class CharactsFieldsValues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'charactsFieldsValues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['productId', 'fieldId'], 'integer'],
            [['fieldId', 'fieldName'], 'required'],
            [['fieldName', 'data'], 'string'],
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
            'fieldId' => 'Field ID',
            'fieldName' => 'Field Name',
            'data' => 'Data',
        ];
    }
}
