<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tags_preset".
 *
 * @property int $id
 * @property string $tag
 * @property string $itemType
 */
class TagsPreset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags_preset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag', 'itemType'], 'required'],
            [['tag', 'itemType'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag' => 'Tag',
            'itemType' => 'Item Type',
        ];
    }
}
