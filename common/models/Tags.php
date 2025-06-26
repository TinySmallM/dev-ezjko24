<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property int $itemId
 * @property string $itemType
 * @property string|null $tags
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'itemType'], 'required'],
            [['itemId'], 'integer'],
            [['itemType', 'tags'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemId' => 'Item ID',
            'itemType' => 'Item Type',
            'tags' => 'Tags',
        ];
    }
}
