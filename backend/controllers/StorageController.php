<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\File;
use tpmanc\imagick\Imagick;

class StorageController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function beforeAction($action){
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
	public function actionIndex(){
		return $this->render('index',[]);
	}

    public function actionUpload(){

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        setlocale (LC_ALL, array ('ru_RU.utf-8', 'rus_RUS.utf-8')); //Basename cyrillic fix
    	
    	$typeValid = ['image/png','image/jpg','image/jpeg','image/gif','image/bmp'];
        $uploads_dir = Yii::$app->basePath.'/../frontend/web/upload/';
        $wm = Yii::$app->basePath.'/../frontend/web/img/wm.png';
        $wm2 = Yii::$app->basePath.'/../frontend/web/img/wm2.png';

        $res = [];

        if(empty($_FILES['file'])) return ['error'=>'Файлы не найдены'];
		
		foreach ($_FILES['file']['error'] as $key=>$val) {

			
			if ( $_FILES['file']['error'][$key] == 0 && in_array($_FILES['file']['type'][$key],$typeValid) ) {

                $f = [
                    'error' => $_FILES['file']['error'][$key],
                    'name' => $_FILES['file']['name'][$key],
                    'size' => $_FILES['file']['size'][$key],
                    'tmp_name' => $_FILES['file']['tmp_name'][$key],
                    'type' => $_FILES['file']['type'][$key]
                ];

                //Если нужно конвертнуть png в jpg
                //if($f['type'] == 'image/png'){
                //    $this->pngToJPG($f["tmp_name"],60);
                //    $f["name"] = str_replace('png','jpg',$f["name"]);
                //}

                $file = pathinfo($f["name"]);
                $img = Imagick::open($f["tmp_name"]);
                $width = $img->getWidth();
                $newName = substr( md5(time().basename($f['name'])),0,10 );

                $img->setImageCompression(8,60);

                //Save orig
                //copy( $f["tmp_name"] , $uploads_dir.'orig_hidden/'.$newName.'.'.$file['extension']);

                //Save thumb
                $img->thumb(450, false)->saveTo($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);

                //$this->imgToWebp ( $uploads_dir.'thumb_'.$newName.'.'.$file['extension'] , $uploads_dir.'thumb_'.$newName.'.webp' , 80 );
                //if need wm
                /*if( Yii::$app->request->getBodyParam('watermark') == 1 ){
                    $imgThumbWm = Imagick::open($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);
                    $imgThumbWm->watermark($wm2, 'center', 'center', '40%', 'auto')
                        ->saveTo($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);
                }*/
                
                //Save norm
                $img2 = Imagick::open($f["tmp_name"]);
                $img2->setImageCompression(8,90);
                if($width > 1920) $width = 1920;

                /*if( Yii::$app->request->getBodyParam('watermark') == 1 ){
                $img2->resize($width, false)
                    ->watermark($wm, 'center', 'center', '60%', 'auto')
                    ->saveTo($uploads_dir.$newName.'.'.$file['extension']);
                }
                else {*/
                    $img2->resize($width, false)
                    ->saveTo($uploads_dir.$newName.'.'.$file['extension']);                    
                //}

                //$this->addWm(Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.'.$file['extension'],Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$newName.'.'.$file['extension']);
                //$this->addWm(Yii::$app->basePath.'/../frontend/web/upload/'.$newName.'.'.$file['extension'],Yii::$app->basePath.'/../frontend/web/upload/'.$newName.'.'.$file['extension']);

                //$this->imgToWebp ( $uploads_dir.$newName.'.'.$file['extension'] , $uploads_dir.$newName.'.webp' , 80 );

                $res[] = ['file'=>$newName.'.'.$file['extension'],'thumb'=>'thumb_'.$newName.'.'.$file['extension']];
			}

            unset($img);
            unset($img2);
            unset($file);
            unset($width);
            unset($newName);
            unset($f);
		}

        return ['files'=>$res];
    }

    private function pngToJPG($filePath,$quality){
        $image = imagecreatefrompng($filePath);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagejpeg($bg, $filePath, $quality);
        imagedestroy($bg);
    }

    private function imgToWebp($filePath,$destination,$quality){
        $info = getimagesize($filePath);

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') 
          $image = imagecreatefromjpeg($filePath);
      
        elseif ($info['mime'] == 'image/gif') 
          $image = imagecreatefromgif($filePath);
      
        elseif ($info['mime'] == 'image/png') 
          $image = imagecreatefrompng($filePath);
      
        $res = imagewebp ( $image , $destination, $quality );
        return $res;
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

    /*
    // Compress jpeg image
    private function compressJpgImage($filePath, $quality) {
        $image = imagecreatefromjpeg($filePath);
        imagejpeg($image, $filePath, $quality);
        imagedestroy($image);
    }
    */

}
