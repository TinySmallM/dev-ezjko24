<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $h1
 * @property string|null $description
 * @property string|null $url
 * @property string|null $content
 * @property int|null $menuindex
 * @property int|null $menushow
 * @property string|null $template
 * @property int|null $parent
 * @property string|null $menuname
 * @property string|null $image
 * @property string|null $backimg
 * @property int|null $published
 * @property int|null $deleted
 * @property int|null $sortId
 * @property string|null $kinescope_id
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'content', 'kinescope_id'], 'string'],
            [['menuindex', 'menushow', 'parent', 'published', 'deleted','template','sortId'], 'integer'],
            [['title', 'h1', 'url', 'menuname', 'image', 'backimg'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'h1' => 'H1',
            'description' => 'Description',
            'url' => 'Url',
            'content' => 'Content',
            'menuindex' => 'Menuindex',
            'menushow' => 'Menushow',
            'template' => 'Template',
            'parent' => 'Parent',
            'menuname' => 'Menuname',
            'image' => 'Image',
            'backimg' => 'Backimg',
            'published' => 'Published',
            'deleted' => 'Deleted',
            'sortId' => 'Sort Id',
            'kinescope_id' => 'kinescope_id'
        ];
    }
    
    public function getchunk(){
		return $this->hasMany(Chunk::className(), ['itemId' => 'id'])->where(['itemType'=>1]);
	}
	
	public function getchild(){
		return $this->hasMany(static::className(), ['parent' => 'id']);
    }
    
    public function getparent(){
		return $this->hasOne(static::className(), ['id' => 'parent']);
	}
	
	public function getproduct(){
		return $this->hasMany(Product::className(), ['id' => 'productId'])
            ->viaTable('page_product', ['pageId' => 'id']);
	}

    public function getcharactsfields(){
		return $this->hasMany(CharactsFields::className(), ['pageId' => 'id']);
    }

}
