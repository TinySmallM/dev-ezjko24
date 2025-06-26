<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "templateBlock".
 *
 * @property int $id
 * @property int $templateId
 * @property string $name
 * @property string|null $content
 * @property int|null $sortId
 */
class TemplateBlock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'templateBlock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['templateId', 'name'], 'required'],
            [['templateId', 'sortId'], 'integer'],
            [['content','chunk'], 'string'],
            [['name'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'templateId' => 'Template ID',
            'name' => 'Name',
            'content' => 'Content',
            'chunk' => 'Chunk',
            'sortId' => 'Sort ID',
        ];
    }
}
