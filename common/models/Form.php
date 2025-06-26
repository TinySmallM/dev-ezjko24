<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form".
 *
 * @property int $id
 * @property int $formId
 * @property string $data
 * @property int $dateCreated
 * @property string $clientIp
 * @property int|null $notify
 */
class Form extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formId', 'data', 'dateCreated', 'clientIp'], 'required'],
            [['formId', 'dateCreated', 'notify'], 'integer'],
            [['data'], 'string'],
            [['clientIp'], 'string', 'max' => 35],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'formId' => 'Form ID',
            'data' => 'Data',
            'dateCreated' => 'Date Created',
            'clientIp' => 'Client Ip',
            'notify' => 'Notify',
        ];
    }
}
