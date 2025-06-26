<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "charactsFields".
 *
 * @property int $id
 * @property int $pageId
 * @property string $name
 * @property string $nameRu
 * @property string|null $defaultVal
 * @property string|null $type
 * @property string|null $groupName
 * @property int|null $isHidden
 * @property string|null $options
 */
class CharactsFields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'charactsFields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pageId', 'name', 'nameRu'], 'required'],
            [['pageId', 'isHidden'], 'integer'],
            [['name', 'nameRu', 'defaultVal', 'type', 'groupName', 'options'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pageId' => 'Page ID',
            'name' => 'Name',
            'nameRu' => 'Name Ru',
            'defaultVal' => 'Default Val',
            'type' => 'Type',
            'groupName' => 'Group Name',
            'isHidden' => 'Is Hidden',
            'options' => 'Options',
        ];
    }
}
