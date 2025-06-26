<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\httpclient\Client;
use yii\Helpers\ArrayHelper;
use common\models\Product;
use common\models\Page;
use common\models\Chunk;
use common\models\PageProduct;
use common\models\ProductImage;
use common\models\Helpers;
use common\models\User;
use tpmanc\imagick\Imagick;

	
class ParseController extends Controller {

    public function actionParse(){

      $itemsArr = json_decode( file_get_contents(Yii::$app->basePath.'/../avito_items.json'), 1);

      $count_items = 0;

      $bef_ok = null;

      foreach($itemsArr as $id_key=>$item){

        $count_items++;
        
        $isNew = null;

        //if($id_key == 'https://www.avito.ru/voronezh/gruzoviki_i_spetstehnika/mercedes-benz_atego_1518_2011_2228999993') $bef_ok = true;
        
        //if(!$bef_ok) continue;

        

        $pr = Product::find()->where(['avito_url'=>$id_key])->one();
        if(!$pr){
            //continue;
            $pr = new Product;
            $pr->avito_url = $id_key;
            
            $pr->template = 5;
            $pr->published = 1;
            $pr->menushow = 1;
            $pr->save();
        }
        else {
          echo(PHP_EOL.'Page already exists with id: '.$id_key.PHP_EOL);
          continue;
        }

        if( isset( $item['h1']) ){
            $pr->h1 = $item['h1'];
            $pr->title = $item['h1'];
            $pr->menuname = $item['h1'];
            $pr->url = $this->pageUrl($item['h1']);
        }

        if( isset( $item['price']) ){
            $pr->price1_sum = $item['price'];
            $pr->price1_name = 'шт';
            $pr->save();
        }

        if( isset( $item['desc']) ){
            $pr->content = $item['params'][0];
            $pr->content .= $item['desc'];
            $pr->save();
        }

        if( isset( $item['imgs']) ){

            foreach($item['imgs'] as $img_url){

                if(!$img_url) continue;

                $res_photo = $this->productPhoto($img_url,$pr->id);
                if(!$pr->image) $pr->image = $res_photo;

            }

            $pr->save();

        }

        //if($id_key < 3122) continue;

        //die();

        echo(PHP_EOL.'Processing '.($count_items).'/'.count($itemsArr).', id: '.$id_key.PHP_EOL);

      }
    }

    private function pageUrl($name){

		return Helpers::translit($name);
		
	}

    private function mcTableToData($table){

      $arrText = [];
      $arrLast = [];
      $clearList = ['Купить промо-баллы',' ПроголосоватьПолучить код кнопки'];

      $DOM = new \DOMDocument;
      $DOM->loadHTML($table);

      $items = $DOM->getElementsByTagName('tr');

      foreach ($items as $key=>$node) {
          foreach($node->childNodes as $chNode){
            $arrText[$key][] = $chNode->textContent;
          }
      }

      foreach($arrText as $arrTextItem){

        foreach($clearList as $ci){
          $arrTextItem[1] = str_replace($ci,'',$arrTextItem[1]);
        }

        $arrLast[ str_replace(':','',$arrTextItem[0]) ] = $arrTextItem[1];
      }

      if( isset($arrLast['IP адрес']) && mb_strpos($arrLast['IP адрес'],'.') == false ) $arrLast['IP адрес'] = 'launcher';

      return $arrLast;


    }

    public function productPhoto($url_external,$productId){

        print_r('Image download '.$url_external.PHP_EOL);
      
        $url = parse_url($url_external);
        $urlStripped = explode('/',$url['path']);

        $newName = $productId.'_'.md5($url_external);

        $res = file_put_contents(Yii::$app->basePath.'/../frontend/web/upload/temp/'.$newName, fopen($url_external, 'r'));

        
        if($res) {
            $im = imagecreatefromjpeg(Yii::$app->basePath.'/../frontend/web/upload/temp/'.$newName);
            imagejpeg($im, Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.jpg', 100);
            imagejpeg($im, Yii::$app->basePath.'/../frontend/web/upload/'.$newName.'.jpg', 100);

            $this->addWm(Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.jpg',Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.jpg');
            $this->addWm(Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.jpg',Yii::$app->basePath.'/../frontend/web/upload/'.$newName.'.jpg');

            imagedestroy($im);

            $pp = new ProductImage;
            $pp->productId = $productId;
            $pp->image = $newName.'.jpg';
            $pp->save();
            return $newName.'.jpg';
        }
        else {
          echo('Image NOT downloaded : '.$url_external.PHP_EOL);
        }

    }

    private function addWm($inputFile,$outputFile){
      //$img=imagecreatefromjpeg($input);
      //$logo=imagecreatefromjpeg(Yii::$app->basePath.'/../frontend/web/wm_small.jpg');
      //imagecopy($img,$logo,0,0,0,0,120,180);

      $image = Imagick::open($inputFile);
      $wm = Imagick::open(Yii::$app->basePath.'/../frontend/web/wm.jpg');

      $wmResizeFactor = 10;

      $img_Width = $image->getWidth();
      $img_Height = $image->getHeight();
      $wm_Width = $wm->getWidth();
      $wm_Height = $wm->getHeight();

      $newWmWidth = $wm_Width / $wmResizeFactor;
      $newWmHeight = $wm_Height / $wmResizeFactor;

      // Draw on the top right corner of the original image
      $x = ($img_Width - $newWmWidth);
      $y = ($img_height - $newWmHeight);

      // Draw the watermark on your image
      $image->watermark(Yii::$app->basePath.'/../frontend/web/wm.jpg', 'right', 'bottom', '30%', 'auto')
      ->saveTo($outputFile);

      return true;

    }

    public function actionRemoveWm(){

      set_time_limit(0);


      print_r(get_loaded_extensions());


      die();

      $items = McSrvPhoto::find()-asArray()->all();

      $origDir = Yii::$app->basePath.'/../frontend/web/upload/photo/';
      $tempDir = Yii::$app->basePath.'/../frontend/web/temp/';

      foreach($items as $d){
        $type = exif_imagetype($origDir.$d->url);

        $newName = $d->url;

        if($type == IMAGETYPE_PNG){
          $newName = str_replace('.png','.jpg',$newName);
          $this->pngToJPG($origDir.$d->url,80,$tempDir.$newName);
          echo('Converted to JPG'.PHP_EOL);
          $this->addWm($tempDir.$newName,$tempDir.$newName);
        }
        else {
          $this->addWm($origDir.$newName,$tempDir.$newName);
        }

        

        

      }
    }

    
    private function pngToJPG($filePath,$quality,$filePathExport){
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagejpeg($bg, $filePathExport, $quality);
        imagedestroy($bg);
    }

    public function actionQc(){
      $url = 'https://q-craft.ru/minecraft-kody';
      $page = file_get_contents($url);

      $doc = new \DOMDocument();
      $doc->loadHTML($page);

      $content = null;
      $h1 = null;
      foreach($dom->getElementsByTagName('div') as $tr) {
          if ( ! $tr->hasAttribute('class')) {
            continue;
          }

          $class = explode(' ', $tr->getAttribute('class'));

          if (in_array('content-block', $class)) {
            $h1 = $tr->getElementsByTagName('h1')->nodeValue;
          }
      }

      print_r( $h1 );

    }

}