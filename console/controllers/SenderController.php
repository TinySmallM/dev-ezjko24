<?php
	namespace console\controllers;
	
	use Yii;
	use yii\console\Controller;
	use yii\httpclient\Client;
	use yii\Helpers\ArrayHelper;
	use common\models\Product;
  use common\models\Coupon;
	use common\models\Page;
	use common\models\Chunk;
	use common\models\PageProduct;
	use common\models\ServPredicate;
	use common\models\ProductImage;
	use common\models\Helpers;
	use common\models\User;
	use tpmanc\imagick\Imagick;

  use common\models\Fingerprint;
  use common\models\FingerprintAction;
  use common\models\Order;
  use common\models\Member;

  use common\models\OrderItem;

  use common\models\FingerprintUserdata;
	
	class SenderController extends Controller {


    public function actionMass(){

    $ord = Member::find()
      ->where(['isEmailSubscribed'=>1])
      ->select(['email'])
      ->column();

    $array = array_map('strtolower', $ord);
    $array = array_unique($ord);

    

    //array_push($array,'contact@smedia.one');

    //$array = ['mr.spalsh29@gmail.com'];


    //$ord = array_merge($ord,$array);
    //$ord = array_unique($ord);

    //print_r(count($ord));
    //return;

    $data = [];

    foreach($array as $key=>$m){

      if($m == 'contact@smedia.one') continue;

      //if( in_array($m,$ord) ) continue;

      //if($m != 'contact@smedia.one') continue;

      echo(PHP_EOL.count($array).'/'.($key+1).' '.$m.': ');
      $file_add = count($array).'/'.($key+1).' '.$m.': ';
      

      if(filter_var($m, FILTER_VALIDATE_EMAIL)){

        Yii::$app->mailer->view->params['email_to'] = $m;
        //Yii::$app->mailer->headers->addHeader('List-Unsubscribe', '<'.Helpers::unsubscribeLink($m).'>');

        $res = Yii::$app->mailer->compose('emailMass4',['c'=>$m])
          ->setHeaders([
            'List-Unsubscribe'=>'<mailto:unsubscribe@domain.com?subject=unsubscribe>,<'.Helpers::unsubscribeLink($m).'>'
          ])
          ->setFrom(Yii::$app->params['mail']['from'])
          ->setTo($m)
          ->setSubject('🪐⚡️ Бесплатный вебинар по Латыни: начинаем в 13:00 МСК')
          ->send();
        echo($res);
        $file_add .= $res;
      }
      else {
        echo('not valid email');
        $file_add .= 'not valid email';
      }

      file_put_contents('/var/www/site_main/data/www/christmedschool.com/email_res.txt', $file_add.PHP_EOL, FILE_APPEND | LOCK_EX);

      echo(PHP_EOL);
    }
      
    }


		
	}