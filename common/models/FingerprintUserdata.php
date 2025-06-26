<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fingerprint_userdata".
 *
 * @property int $id
 * @property int $fingerprintId
 * @property string $name
 * @property string|null $value
 */
class FingerprintUserdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fingerprint_userdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fingerprintId', 'name'], 'required'],
            [['fingerprintId'], 'integer'],
            [['name', 'value'], 'string'],
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
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
