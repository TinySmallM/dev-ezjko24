<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $name Название
 * @property string $content Содержимое
 * @property string $image Картинка превью
 * @property int $dateCreated Дата создания
 * @property int $dateEdited Дата редактирования
 * @property int $isPublished Is published
 * @property int $isDeleted Is Deleted
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['content'], 'string'],
            [['isPublished', 'isDeleted'], 'integer'],
            [['dateCreated', 'dateEdited'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 100],
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
            'content' => 'Content',
            'image' => 'Image',
            'dateCreated' => 'Date Created',
            'dateEdited' => 'Date Edited',
            'isPublished' => 'Is Published',
            'isDeleted' => 'Is Deleted',
        ];
    }
}
