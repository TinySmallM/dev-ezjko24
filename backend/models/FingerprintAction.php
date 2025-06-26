<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fingerprint_action".
 *
 * @property int $id
 * @property int $fingerprintId
 * @property int $type
 * @property int $url
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
            [['id', 'fingerprintId', 'type', 'url'], 'required'],
            [['id', 'fingerprintId', 'type', 'url'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fingerprintId' => 'Fingerprint ID',
            'type' => 'Type',
            'url' => 'Url',
        ];
    }
}
