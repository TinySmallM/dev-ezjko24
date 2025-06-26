<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $original_name
 * @property int $weight
 * @property string $local_name
 * @property string $original_extension
 * @property string $created_at
 * @property string|null $item_name
 * @property int|null $item_id
 * @property int|null $item_sortId
 * @property string|null $dir
 * @property string $hash
 * @property string|null $kinescope_data
 * @property string|null $kinescope_id
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['original_name', 'weight', 'local_name', 'original_extension','hash'], 'required'],
            [['original_name', 'local_name', 'original_extension', 'item_name', 'anchor', 'dir','hash', 'kinescope_data', 'kinescope_id'], 'string'],
            [['weight', 'item_id','item_sortId'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_name' => 'Original Name',
            'weight' => 'Weight',
            'local_name' => 'Local Name',
            'original_extension' => 'Original Extension',
            'created_at' => 'Created At',
            'item_name' => 'Item Name',
            'item_id' => 'Item ID',
            'anchor' => 'Anchor',
            'item_sortId' => 'item_sortId',
            'dir' => 'Dir',
            'hash' =>'Hash',
            'kinescope_data' => 'Kinescope Data',
            'kinescope_id' => 'Kinescope ID'
        ];
    }

    public static function findByHash($str)
    {
        return static::findOne(['hash' => $str]);
    }

    public function getplatformpage(){
        return $this->hasOne(PlatformPage::className(), ['id' => 'item_id']);
	}
}
