<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property string $name
 * @property string $file
 * @property int $dateEdited
 * @property string|null $description
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'file', 'dateEdited'], 'required'],
            [['dateEdited'], 'integer'],
            [['name', 'file'], 'string', 'max' => 1000],
            [['description'], 'string', 'max' => 2500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'file' => 'File',
            'dateEdited' => 'Date Edited',
            'description' => 'Description',
        ];
    }
    
    public function getblock(){
		return $this->hasMany(TemplateBlock::className(), ['templateId' => 'id']);
	}
}
