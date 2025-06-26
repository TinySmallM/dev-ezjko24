<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $id_1c ID в 1С
 * @property string|null $title Title страницы
 * @property string|null $h1 H1 страницы
 * @property string|null $description Description страницы
 * @property string|null $url Url страницы
 * @property int|null $menuindex Сортировка
 * @property int|null $menushow Отображать в меню
 * @property int|null $template ID Шаблона
 * @property string|null $menuname Название в меню/в плитке
 * @property string|null $image Картинка
 * @property string|null $artikul1 Артикул – цена 1
 * @property string|null $artikul2 Артикул – цена 2
 * @property int|null $nds Ставка НДС
 * @property float|null $price1_sum Ед. изм #1 - цена
 * @property string|null $price1_name Ед. изм #1 - название
 * @property float|null $price2_sum Ед. изм #2 - цена
 * @property string|null $price2_name Ед. изм #2 - название
 * @property int|null $price2_mincount Ед. изм #2 - количество в упак
 * @property string|null $content Описание товара
 * @property int|null $published Опубликован
 * @property int|null $deleted Удален
 * @property string $updatedon Изменен (кэш)
 * @property string|null $avito_url Описание товара
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'content', 'avito_url'], 'string'],
            [['menuindex', 'menushow', 'template', 'nds', 'price2_mincount', 'published', 'deleted'], 'integer'],
            [['price1_sum', 'price2_sum'], 'number'],
            [['updatedon'], 'safe'],
            [['id_1c', 'title', 'h1', 'url', 'menuname', 'image', 'artikul1', 'artikul2'], 'string', 'max' => 255],
            [['price1_name', 'price2_name'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_1c' => 'Id 1c',
            'title' => 'Title',
            'h1' => 'H1',
            'description' => 'Description',
            'url' => 'Url',
            'menuindex' => 'Menuindex',
            'menushow' => 'Menushow',
            'template' => 'Template',
            'menuname' => 'Menuname',
            'image' => 'Image',
            'artikul1' => 'Artikul1',
            'artikul2' => 'Artikul2',
            'nds' => 'Nds',
            'price1_sum' => 'Price1 Sum',
            'price1_name' => 'Price1 Name',
            'price2_sum' => 'Price2 Sum',
            'price2_name' => 'Price2 Name',
            'price2_mincount' => 'Price2 Mincount',
            'content' => 'Content',
            'published' => 'Published',
            'deleted' => 'Deleted',
            'updatedon' => 'Updatedon',
        ];
    }
    
    public function getchunk(){
		return $this->hasMany(Chunk::className(), ['itemId' => 'id'])->where(['itemType'=>2]);
	}
	
	public function getpage(){
		return $this->hasMany(Page::className(), ['id' => 'pageId'])
            ->viaTable('page_product', ['productId' => 'id']);
    }
    
    public function getgallery(){
		return $this->hasMany(ProductImage::className(), ['productId' => 'id'])->orderBy(['orderId'=>SORT_ASC]);
	}
	
	public function getoption(){
		return $this->hasMany(ProductOption::className(), ['productId' => 'id']);
	}

    public function getcharactsfieldsvalues(){
		return $this->hasMany(CharactsFieldsValues::className(), ['productId' => 'id']);
	}
}
