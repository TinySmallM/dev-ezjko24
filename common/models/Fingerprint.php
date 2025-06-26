<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fingerprint".
 *
 * @property int $id
 * @property string $uid_backend
 * @property string|null $uid_frontend
 * @property string|null $ya_client_id_frontend
 * @property string|null $os_backend
 * @property string|null $device_backend
 * @property string|null $user_agent_backend
 * @property string|null $browser_backend
 * @property int|null $is_robot_backend
 * @property string|null $lang_backend
 * @property string $ip_backend
 * @property string|null $browser_frontend
 * @property string|null $canvas_frontend
 * @property int|null $cookie_frontend
 * @property string|null $display_frontend
 * @property string|null $language_frontend
 * @property string|null $os_frontend
 * @property int|null $timezone_frontend
 * @property int|null $touch_frontend
 * @property string|null $useragent_frontend
 * @property string|null $device_type_backend
 */
class Fingerprint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fingerprint';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid_backend', 'ip_backend'], 'required'],
            [['id', 'is_robot_backend', 'cookie_frontend', 'timezone_frontend', 'touch_frontend'], 'integer'],
            [['ya_client_id_frontend', 'os_backend', 'device_backend', 'device_type_backend', 'os_frontend', 'user_agent_backend', 'browser_backend', 'lang_backend', 'ip_backend', 'browser_frontend', 'canvas_frontend', 'display_frontend', 'language_frontend', 'useragent_frontend'], 'string'],
            [['uid_backend', 'uid_frontend'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid_backend' => 'Uid Backend',
            'uid_frontend' => 'Uid Frontend',
            'ya_client_id' => 'Ya Client ID',
            'os_backend' => 'Os Backend',
            'device_backend' => 'Device Backend',
            'user_agent_backend' => 'User Agent Backend',
            'browser_backend' => 'Browser Backend',
            'is_robot_backend' => 'Is Robot Backend',
            'lang_backend' => 'Lang Backend',
            'ip_backend' => 'Ip Backend',
            'browser_frontend' => 'Browser Frontend',
            'canvas_frontend' => 'Canvas Frontend',
            'cookie_frontend' => 'Cookie Frontend',
            'display_frontend' => 'Display Frontend',
            'language_frontend' => 'Language Frontend',
            'os_frontend' => 'Os Frontend',
            'timezone_frontend' => 'Timezone Frontend',
            'touch_frontend' => 'Touch Frontend',
            'useragent_frontend' => 'Useragent Frontend',
            'device_type_backend' => 'device type backend'
        ];
    }
    
    public function getaction(){
		return $this->hasMany(FingerprintAction::className(), ['fingerprintId' => 'id']);
	}
}
