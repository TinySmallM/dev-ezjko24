<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fingerprint_action".
 *
 * @property int $id
 * @property int $groupId
 * @property int $fingerprintId
 * @property string $type
 * @property string $url
 * @property int $dateCreated
 * $property string params
 */
class FingerprintAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fingerprint_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fingerprintId', 'type', 'url','dateCreated'], 'required'],
            [['id', 'fingerprintId','dateCreated','groupId' ], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupId'=>'group Id',
            'fingerprintId' => 'Fingerprint ID',
            'type' => 'Type',
            'url' => 'Url',
            'dateCreated' => 'date Created',
            'params' => 'Params'
        ];
    }
}
