<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chunk".
 *
 * @property int $id
 * @property int $itemId
 * @property int $itemType
 * @property string $name
 * @property string|null $content
 */
class Chunk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chunk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        	[['itemId','itemType'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 55],
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
            'content' => 'Content',
            'itemType' => 'Item Type',
            'name' => 'Name'
        ];
    }
}
