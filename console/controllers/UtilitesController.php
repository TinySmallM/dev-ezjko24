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
  use common\models\MemberStats;

  use common\models\OrderItem;

  use common\models\PlatformPage;
  use common\models\PlatformAccess;

  use common\models\FingerprintUserdata;
  use common\models\File;
  use common\models\ProductCharacts;
  use common\models\TagsPreset;
	
	class UtilitesController extends Controller {
		public function actionProduct(){

      $h_dir = $_SERVER['DOCUMENT_ROOT'].'console/controllers/product/';
			
			$pr = [];


      foreach($pr as $pr_i=>$pr_d){

        if( is_dir($h_dir.$pr_d['dir'].'/слайдер в карточке проекта') ){
          $pr[$pr_i]['slides'] = scandir($h_dir.$pr_d['dir'].'/слайдер в карточке проекта');
          $pr[$pr_i]['slidesDirName'] = 'слайдер в карточке проекта';
        }
        else {
          $pr[$pr_i]['slides'] = scandir($h_dir.$pr_d['dir'].'/Слайдер в карточке проекта');
          $pr[$pr_i]['slidesDirName'] = 'Слайдер в карточке проекта';
        }
        
        $pr[$pr_i]['desc'] = file_get_contents($h_dir.$pr_d['dir'].'/Описание.txt');
        $pr[$pr_i]['descImg'] = $h_dir.$pr_d['dir'].'/описание проекта.jpg';
        $pr[$pr_i]['finishedSlides'] = scandir($h_dir.$pr_d['dir'].'/Фото готовых домов');

      }

              
      foreach($pr as $pr_i=>$pr_d){
        echo('Processing id'.$pr_i.PHP_EOL);
        
        //Создаем товар
        $item = new Product();
        
        $item->url = Helpers::translit($pr_d['name']);
        $item->title = $pr_d['name'];
        $item->h1 = $pr_d['name'];
        $item->description = $pr_d['name'];
        $item->menuname = $pr_d['name'];
        $item->template = 3;
        $item->published = 1;
        $item->menushow = 1;
          
        $item->price1_sum = preg_replace("/[^0-9]/", '', $pr_d['price_new']);
        $item->price2_sum = preg_replace("/[^0-9]/", '', $pr_d['price_old']);

        if(!$item->save()) var_dump( $item->getErrors() );
        
        $item->id = $item->id;
        
        //Создаем линки к категориям
        $catLink = new PageProduct;
        $catLink->productId = $item->id;
        $catLink->pageId = 1030;
        if(!$catLink->save()) var_dump( $catLink->getErrors() );
        
        //Обрабатываем характеристики
        foreach(['char_square','char_floors','char_bathroom','char_sauna','char_bed','char_bedcase'] as $k){
          $ch = new Chunk;
          $ch->itemType = 2;
          $ch->itemId = $item->id;
          $ch->name = $k;
          $ch->content = $k=='char_square'?$pr_d['chunk.'.$k].' м2':$pr_d['chunk.'.$k];
          if(!$ch->save()) var_dump( $ch->getErrors() );
        }

        //Загружаем фото в слайдер
        foreach($pr_d['slides'] as $img){
          if($img == '.' || $img == '..') continue;
          $name = $this->upload($h_dir.$pr_d['dir'].'/'.$pr_d['slidesDirName'].'/'.$img);
          if($name){
            $pi = new ProductImage;
            $pi->productId = $item->id;
            $pi->image = $name;
            if(!$pi->save()) var_dump( $pi->getErrors() );
          }
        }

        //Обрабатываем описание
        $ch = new Chunk;
        $ch->itemType = 2;
        $ch->itemId = $item->id;
        $ch->name = 'content_block1_text';
        $ch->content = '<p>'.preg_replace('/[\x00-\x1F\x7F]/u', '', $pr[$pr_i]['desc']).'</p>';
        if(!$ch->save()) var_dump( $ch->getErrors() );       

        //Обрабатываем фото в блок с описанием
        $dpn = $this->upload($pr[$pr_i]['descImg']);
        if($dpn){
          $ch = new Chunk;
          $ch->itemType = 2;
          $ch->itemId = $item->id;
          $ch->name = 'content_block1_image';
          $ch->content = $dpn;
          if(!$ch->save()) var_dump( $ch->getErrors() ); 
        }      

        //Обрабатываем фото в блок с примерами готовых домов
        if($pr_d['name'] != 'Майя' && $pr_d['name'] != 'Станислав Шале'){
          $fhChunkArr = [];
          foreach($pr_d['finishedSlides'] as $img){
            if($img == '.' || $img == '..') continue;
            $name = $this->upload($h_dir.$pr_d['dir'].'/Фото готовых домов'.'/'.$img);
            if($name) $fhChunkArr[] = ["id"=>0,"image"=>$name,"description"=>null,"orderId"=>null,"productId"=>null];
          }
          $ch = new Chunk;
          $ch->itemType = 2;
          $ch->itemId = $item->id;
          $ch->name = 'finished_house';
          $ch->content = json_encode($fhChunkArr);
          if(!$ch->save()) var_dump( $ch->getErrors() ); 
        }

      }

      var_dump($pr);
			
		}
		
		public function actionName(){
			//$pr = Product::find()->with(['page'])->where(['id'=>74])->asArray()->one();
			
			$meta = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'console/controllers/meta.txt'), 1);
			$meta = $meta['items'];

			foreach($meta as $d){
				
				echo('url: '.$d['url'].PHP_EOL);
				
				$p = Page::find()->where([ 'url'=>str_replace('/','',$d['url'] )])->one();
				if(!$p) {
					echo('Not found'.PHP_EOL);
					continue;
				}
				
				$p->title = $d['title'];
				$p->h1 = $d['h1'];
				$p->save();
				
				echo('Saved'.PHP_EOL);
				
			}
		}
		
		
		private function upload($fileDir){
			
			setlocale (LC_ALL, array ('ru_RU.utf-8', 'rus_RUS.utf-8')); //Basename cyrillic fix
			$uploads_dir = Yii::$app->basePath.'/../frontend/web/upload/';
			$newName = substr( md5(time().basename($fileDir)),0,10 );
			$file = pathinfo($fileDir);
			
			$type = exif_imagetype($fileDir);
			
			//Если нужно конвертнуть png в jpg
            if($type == IMAGETYPE_PNG){
                $this->pngToJPG($fileDir,60);
                $fileDir = str_replace('png','jpg',$fileDir);
            }
	    	
	    	//$typeValid = ['image/png','image/jpg','image/jpeg','image/gif','image/bmp'];
	        
	        //$wm = Yii::$app->basePath.'/../frontend/web/img/wm.png';
	        //$wm2 = Yii::$app->basePath.'/../frontend/web/img/wm2.png';
	
            


            //$file = pathinfo($key["name"]);
            $img = Imagick::open($fileDir);
            $width = $img->getWidth();
            
            //Save orig
            //copy( $fileDir , $uploads_dir.'orig_hidden/'.$newName.'.'.$file['extension']);

            //Save thumb
            $img->thumb(450, false)->saveTo($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);
            //if need wm
            /*
            if( Yii::$app->request->getBodyParam('watermark') == 1 ){
                $imgThumbWm = Imagick::open($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);
                $imgThumbWm->watermark($wm2, 'center', 'center', '40%', 'auto')
                    ->saveTo($uploads_dir.'thumb_'.$newName.'.'.$file['extension']);
            }
            */
            
            //Save norm
            $img2 = Imagick::open($fileDir);
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
		    
            return $newName.'.'.$file['extension'];

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
		
		public function actionMail(){
			
		   //Helpers::mail('contact@smedia.one','test','test');
		     Yii::$app->mailer->compose('orderNew-html',['id'=>1,'cart'=>[]])
				->setFrom('system@smedia.one')
			    ->setTo('contact@smedia.one')
			    ->setSubject('Заказ #1 в магазине smedia')
			    ->send();
		        
		    //var_dump($s);
		}

		public function actionPages(){

			$pages = [];

			foreach($pages as $p1){
				$itemId1 = $this->addPage($p1['url'],0,$p1['title'],$p1['h1'],null,$p1['menu_name']);
				echo('Page level 1 added: '.$p1['url'].', new id: '.$itemId1).PHP_EOL;
				foreach($p1['items'] as $p2){
					$itemId2 = $this->addPage($p2['url'],$itemId1,$p2['title'],$p2['h1'],null,$p2['menu_name']);
					echo('Page level 2 added: '.$p2['url'].', new id: '.$itemId2).PHP_EOL;
				}
			}

			echo('Completed!').PHP_EOL;

		}

		private function addPage($url, $parentId, $title, $h1, $description, $menuName ){
			$item = new Page;
			$item->url = $url;
			$item->parent = $parentId;
			$item->title = $title;
			$item->h1 = $h1;
			$item->description = $description;
			$item->menuname = $menuName;
			$item->published = 1;
			$item->template = 2; //1-главная, 2 - категория, 3 - товар, 4 - статика
			$item->menushow = 1;
			if(!$item->save()){
				echo $item->getErrors();
				die();
			}
			$item->id = $item->id;
			return $item->id;


		}
		
		public function actionUserPwd($email,$pwd)

	    {
	       $u = User::find()->where(['username'=>$email])->one();
	       if(!$u) {
	           echo('User no exists').PHP_EOL;
	           return;
	       }
	       $u->setPassword($pwd);
	       if($u->save()){
	           echo('Password updated successfully').PHP_EOL;
	       }
	       else echo($u->getError());
	       
	    }

    public function actionClearUploads(){
      $p = PageProduct::find()->where(['pageId'=>1030])->select(['productId'])->column();
      $pi = ProductImage::find()->where(['productId'=>$p])->all();


      //print_r($pi);
      //return;

      //$chunk = Chunk::find()->where(['name'=>'gallery'])->all();

      $list = [];

      foreach($pi as $i=>$d){

        //$data = json_decode($d->content,1);

        //foreach($data as $b=>$k){
          //$list[] = $k['image'];
          if( file_exists( Yii::$app->basePath.'/../frontend/web/upload/'.$d['image'] ) ){
            unlink(Yii::$app->basePath.'/../frontend/web/upload/'.$d['image']);
          }

          if( file_exists( Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$d['image'] ) ){
            unlink(Yii::$app->basePath.'/../frontend/web/upload/thumb_'.$d['image']);
          }
          
          
        //}

        $d->delete();

      }

      //print_r($list);
    }

    public function actionWpCoupon(){
      $data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'promo.txt'), 1);

      $arr = [];

      foreach($data['items'] as $i=>$d){
        
        if(empty($d['post_id'])) continue;
        //print_r($d['title']['__cdata'].PHP_EOL);
        $el = [
          'id'=>$d['post_id'],
          'text'=>$d['title']['__cdata'],
          'dateCreated'=>strtotime($d['post_date']['__cdata']),
          /*
          'discount_type'=>$d['postmeta'][0]['meta_value']['__cdata'],
          'discount_num'=>$d['postmeta'][1]['meta_value']['__cdata'],
          'usage_limit'=>$d['postmeta'][3]['meta_value']['__cdata'],
          'date_expires'=>$d['postmeta'][4]['meta_value']['__cdata'],
          'min_amount'=>$d['postmeta'][7]['meta_value']['__cdata'],
          'max_amount'=>$d['postmeta'][8]['meta_value']['__cdata'],
          'exclude_product_ids'=>$d['postmeta'][11]['meta_value']['__cdata'],
          'email_coupon'=>$d['postmeta'][15]['meta_value']['__cdata'],
          */
        ];

        foreach($d['postmeta'] as $mk=>$md){

          if($md['meta_key']['__cdata'] == '_used_by'){
            if( empty($el['_used_by'] ) ) $el['_used_by'] = [];
            $el['_used_by'][] = $md['meta_value']['__cdata'];
            continue;
          }

          if($md['meta_key']['__cdata'] == 'date_expires' && $md['meta_value']['__cdata']){
            $el[ $md['meta_key']['__cdata'] ] = strtotime($md['meta_value']['__cdata']);
            continue;
          }

          $el[ $md['meta_key']['__cdata'] ] = $md['meta_value']['__cdata'];
        }

        $arr[] = $el;

        
        
      }

      foreach($arr as $d){


        print_r($d['text'].PHP_EOL);

        $c = new Coupon;
        $c->text = $d['text'];
        $c->type = $d['discount_type']=='percent'?'percent':'sum';
        $c->amount = $d['coupon_amount'];
        
        if( !empty($d['minimum_amount']) && $d['minimum_amount'] ){
          $c->minOrderSum = $d['minimum_amount'];
        }

        if( !empty($d['maximum_amount']) && $d['maximum_amount'] ){
          $c->maxOrderSum = $d['maximum_amount'];
        }

        if( array_key_exists('mwb_wgm_giftcard_coupon',$d) ){
          $c->limitUsages = 1;
        }
        else $c->limitUsages = null;
        
        $c->dateCreated = date("y-m-d H:i:s", strtotime( $d['dateCreated'] ) );

        $c->stateId = 1;

        if( !$c->save() ){
          print_r( $c->getErrors() );
        }
        

      }

    
      

      //print_r($arr);
      //file_put_contents($_SERVER['DOCUMENT_ROOT'].'promo_new.txt',json_encode($arr));



    }

    //Раздедяет fingerprintAction на отдельные визиты
    public function actionCollectVisit(){

      //get ids to process
      $fp_ids = FingerprintAction::find()
          ->select(['fingerprintId'])
          ->andWhere(['is', 'firstActionId', new \yii\db\Expression('null')])
          ->column();
        
      $fp_ids = array_unique($fp_ids);

      $fp = Fingerprint::find()->where(['id'=>$fp_ids])->AsArray()->all();

      echo('Total fp: '.count($fp).PHP_EOL);

      foreach($fp as $fp_d){

        echo('Processing id '.$fp_d['id'].PHP_EOL);


        $act = FingerprintAction::find()
          ->where(['fingerprintId'=>$fp_d['id']])
          //->andWhere(['>','dateCreated', strtotime(date('d.m.Y').' 00:00:00')-3600])
          //->andWhere(['is', 'firstActionId', new \yii\db\Expression('null')])
          ->orderBy(['id'=>SORT_ASC])->asArray()->all();
        if(!$act) continue;

        $visits = [];

        $lastId = null;
        $lastTime = null;
        foreach($act as $act_d){
          
          if(!$lastId) {
            $lastId = $act_d['id'];
            $lastTime = $act_d['dateCreated'];
            $visits[] = $act_d;
          }
          
          if( ( ($act_d['dateCreated'] - $lastTime) > 3600) && $act_d['type'] == 'visit' ) {
            $lastId = $act_d['id'];
            $lastTime = $act_d['dateCreated'];
            $visits[] = $act_d;
          }

          if(!$act_d['firstActionId']){
            $fpa_one = FingerprintAction::findOne($act_d['id']);
            $fpa_one->firstActionId = $lastId;
            $fpa_one->save();
          }

        }

      }

    }

    //Привязывает id визита к покупке
    public function actionCollectOrderVisit(){

      $order = Order::find()->where(['is', 'fingerprintFirstActionId', new \yii\db\Expression('null')])->asArray()->all();

      print_r('Total count '.count($order).PHP_EOL);
      foreach($order as $i=>$o_d){

        print_r('Processing id '.$o_d['id'].' ('.($i+1).'/'.count($order).')'.PHP_EOL);
        
        $act = FingerprintAction::find()
        ->where(['fingerprintId'=>$o_d['fingerprintId']])
        ->andWhere(['>','dateCreated',intval($o_d['created'])-120])
        ->orderBy(['id'=>SORT_DESC])->asArray()->one();

        if($act){
          $o_i = Order::findOne($o_d['id']);
          $o_i->fingerprintFirstActionId = $act['firstActionId'];
          $o_i->save();
        }
      }

    }

    //Заполняет данные fingerprint по оплаченным покупкам (имя, email)
    public function actionCollectFpUser(){

      $fp_all = Fingerprint::find()->all();
      
      //Первый проход – сохраняем данные, устанавливаем новые 
      foreach($fp_all as $fp){

        $fp_o = Order::find()
          ->where(['fingerprintId'=>$fp->id,'status'=>[2,3]])
          ->orderBy(['id'=>SORT_ASC])->asArray()->all();

        if($fp_o) echo('Fingerprint: '.$fp->id.PHP_EOL);

        $fp_ud_emails = FingerprintUserdata::find()
          ->where(['fingerprintId'=>$fp->id,'name'=>'email','value'=>$o['email']])
          ->select(['value'])->column();
        
        
        foreach($fp_o as $i=>$o){

          $o['email'] = strtolower( trim($o['email']) );

            //Запоминаем в userdata 
            if( !in_array($o['email'], $fp_ud_emails) ) {
              $fp_ud_email = new FingerprintUserdata;
              $fp_ud_email->fingerprintId = $fp->id;
              $fp_ud_email->name = 'email';
              $fp_ud_email->value = $o['email'];
              $fp_ud_email->save();
              $fp_ud_emails[] = $o['email'];
            }

            $fp->customer_email = $o['email'];
            $fp->customer_name = $o['name'];

            echo('Data set: ' . $o['email'] . ' / ' . $o['name'].PHP_EOL);
        }

        if($fp->customer_email){
          $fp_first = FingerprintUserdata::find()->where(['name'=>'email','value'=>$fp->customer_email])->orderBy(['id'=>SORT_ASC])->one();
          if($fp_first && $fp_first->fingerprintId != $fp->id) $fp->parentFingerprintId = $fp_first->fingerprintId;
        }

        $fp->save();

      }
      
    }

    //Меняет статус у неполаченных заказов на id=4, после которых была оплата по этому же fingerprintId
    public function actionCollectUnpaidOrders(){

      //$date_start = date('Y-m-d').' 00:00:01';

      $date_start = date('2023-01-01').' 00:00:01';

      $o = Order::find()->where(['status'=>3])->andWhere(['>','created',$date_start])->asArray()->all();

      foreach($o as $i=>$d){

        $date_start_local = date_create_from_format( 'U', strtotime($d['created']) )->format('Y-m-d').' 00:00:00';

        $o_nc = Order::find()
          ->where(['status'=>1])
          ->andWhere(['OR',['fingerprintId'=>$d['fingerprintId']],['email'=>$d['email']]])
          ->andWhere(['<','id',$d['id']])
          ->andWhere(['>','created',$date_start_local])
          ->all();

        foreach($o_nc as $o_nc_k=>$o_nc_d){
          $o_nc_d->status = 4;
          $o_nc_d->cancelledByOrderId = $d['id'];
          $o_nc_d->save();
        }

        print_r($d['id'].PHP_EOL);
        print_r( 'Not completed: '.count($o_nc).PHP_EOL.PHP_EOL );
      }


      
      
    }

    public function actionDiscount(){
    
      $pr = array(   
          array("val0"=>"239","val1"=>"Бокс Freshman starter pack light","val2"=>"7990.00","val3"=>"бокс","val4"=>"8970.00","val5"=>"бокс")
      );


      foreach($pr as $i=>$d){
        if($d['val2'] == 'NULL') $d['val2'] = null;
        if($d['val5'] == 'NULL') $d['val5'] = null;
        if($d['val3'] == 'NULL') $d['val3'] = null;
        if($d['val4'] == 'NULL') $d['val4'] = null;

        $prod = Product::findOne($d['val0']);

        $prod->price1_sum = $d['val2'];
        $prod->price1_name = $d['val3'];

        $prod->price2_sum = $d['val4'];
        $prod->price2_name = $d['val5'];

        if(!$prod->save()) print_r($prod->getErrors());

        echo($prod['menuname'].', цена 1: '.$prod->price1_sum.', цена 2: '.$prod->price2_sum.PHP_EOL);

      }
    }

    public function actionDd(){
      $pr = Product::find()->all();
      foreach($pr as $p){
        if($p->price1_name && $p->price2_name && $p->price1_name == $p->price2_name){
          print_r($p->id.', '.$p->menuname.', set price_2: '.$p->price2_name.' ('.$p->price2_sum.')'.PHP_EOL);
          $p->price1_sum = $p->price2_sum;
          $p->price2_name = null;
          $p->price2_sum = null;
          $p->save();
        }
      }
    }

    public function actionUnpaid(){
      $od = Order::find()->where(['status'=>1])
        ->andWhere(['>','created','2023-08-26 23:59:59'])
        ->asArray()->all();

      $unique_email = [];
      $unique_email_total = [];
      $email_without_lk = [];
      foreach($od as $i=>$d){
        $o_next = Order::find()->where(['status'=>3,'email'=>$d['email']])->one();
        $m = Member::find()->where(['email'=>$d['email']])->one();

        if(!in_array($d['email'],$unique_email_total) ) $unique_email_total[] = $d['email'];

        if(!$m && !in_array($d['email'],$email_without_lk)) $email_without_lk[] = $d['email'];

        if(!$o_next && !in_array($d['email'],$unique_email) ) $unique_email[] = $d['email'];
      }

      echo(PHP_EOL.' Unique emails count without buys '.count($unique_email).', from unique '.count($unique_email_total).' emails from '.count($od).' orders');
      echo(PHP_EOL.'Email without lk is '.count($email_without_lk));
    }

    public function actionPageFileVideo(){
      $pages = PlatformPage::find()->all();


      foreach($pages as $i=>$p){
        //print_r($p->id.PHP_EOL);
        //die();
        $regexp = "<video(.*)src=\"([^\"]*)\"(.*)<\/video>";

        if( preg_match_all("/$regexp/siU", $p->content, $matches, PREG_SET_ORDER) ) {

          foreach($matches as $math_key=>$match){

            $match[2] = urldecode($match[2]);

            

            if( strpos($match[2],'forms.gle') || strpos($match[2],'google.com') || strpos($match[2],'quizlet.com') ) continue;

            $match[2] = str_replace('//','/', str_replace('https://cdn.christmedschool.com','',$match[2]) );

            print_r($match[2]);

            $file_path = '/var/www/site_main/data/www/christmedschool.com/frontend/web/host1/chr'.$match[2];

            $file_info = pathinfo($file_path);

            /*$f = File::find()->where(['item_id'=>$p->id,'dir'=>$match[2]])->one();

            $newLink = $match[0];
            $newLink = str_replace('class','cdn-download="'.$f->hash.'" class',$newLink);

            if($f){
              $p->content = str_replace($match[0],$newLink,$p->content);
              $p->save();
            }*/

            $f = File::find()->where(['dir'=>$match[2]])->one();
            if(!$f){
              $f = new File;
              $f->original_name = $file_info['filename'];
              $f->local_name = $file_info['basename'];
              $f->original_extension = $file_info['extension'];
              //$f->anchor = $match[3];
              $f->item_name = 'platform_page';
              $f->item_id = $p['id'];
              $f->weight = filesize($file_path);
              $f->item_sortId = $math_key;
              $f->dir = $match[2];
              $f->hash = md5(Helpers::randStr(32).date('U'));
              if(!$f->save()) {
                print_r('Page id '.$p['id'].' file '.PHP_EOL);
                print_r($match);
                //print_r($f->getErrors());

              }
            } 
            else {
              print_r('EXISTS Page id '.$p['id'].' file '.PHP_EOL);
            }
            
            
          }
        }

        

      }
    }

    public function actionPageVideoReplace(){
      $pages = PlatformPage::find()->all();


      foreach($pages as $i=>$p){
        //print_r($p->id.PHP_EOL);
        //die();
        //$regexp = "<video(.*)src=\"([^\"]*)\"(.*)<\/video>";
        $regexp = "<a(.*)href=\"([^\"]*)\"(.*)<\/a>";

        if( preg_match_all("/$regexp/siU", $p->content, $matches, PREG_SET_ORDER) ) {

          foreach($matches as $math_key=>$match){

            $match[2] = urldecode($match[2]);

            

            if( strpos($match[2],'forms.gle') || strpos($match[2],'google.com') || strpos($match[2],'quizlet.com') ) continue;

            $match[2] = str_replace('//','/', str_replace('https://cdn.christmedschool.com','',$match[2]) );

            //$match[3] = trim( strip_tags($match[3]) );


            $repl = File::find()->where(['dir'=>$match[2]])->asArray()->one();
            if(!$repl) continue;

            //$p->content = str_replace($match[0],'{video::'.$repl['id'].'}',$p->content);
            //$p->save();


            print_r($match[2].' => '.$repl['id'].PHP_EOL);

            $p->content = str_replace($match[0],'{file::'.$repl['id'].'}',$p->content);
            //$p->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $p->content);
            //$p->content = preg_replace('#<button(.*?)>(.*?)</button>#is', '', $p->content);
            $p->save();
            //die();

            //$file_path = '/var/www/site_main/data/www/christmedschool.com/frontend/web/host1/chr'.$match[2];

            //$file_info = pathinfo($file_path);

            /*$f = File::find()->where(['item_id'=>$p->id,'dir'=>$match[2]])->one();

            $newLink = $match[0];
            $newLink = str_replace('class','cdn-download="'.$f->hash.'" class',$newLink);

            if($f){
              $p->content = str_replace($match[0],$newLink,$p->content);
              $p->save();
            }*/

            
            /*$f = new File;
            $f->original_name = $file_info['filename'];
            $f->local_name = $file_info['basename'];
            $f->original_extension = $file_info['extension'];
            //$f->anchor = $match[3];
            $f->item_name = 'platform_page';
            $f->item_id = $p['id'];
            $f->weight = filesize($file_path);
            $f->item_sortId = $math_key;
            $f->dir = $match[2];
            $f->hash = md5(Helpers::randStr(32).date('U'));
            if(!$f->save()) {
              print_r('Page id '.$p['id'].' file '.PHP_EOL);
              print_r($match);
              //print_r($f->getErrors());

            }
            */
            
          }
        }

        

      }
    }

    public function actionKinescopeDir(){
      $pages = PlatformPage::find()->all();

      $KINESCOPE_API_TOKEN = '33adaa3f-ac36-4c5a-8ecd-19b720bf75af';

      foreach($pages as $p){

        if(!$p->courseId) continue;

        $parent_id = Page::find()->where(['id'=>$p->courseId])->select(['kinescope_id'])->column()[0];
      
        $headers = array(
          'Authorization: Bearer ' . $KINESCOPE_API_TOKEN,
        );

        $data = json_encode(array(
          'Content-Type: application/json',
          'name' => $p['h1'],
        ));

        $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_URL, "https://api.kinescope.io/v1/projects/".$parent_id."/folders");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          $result = json_decode(curl_exec($ch), true);
          curl_close($ch);
      
          //print_r($result);

          $p->kinescope_folder_id = $result['data']['id'];
          $p->save();

        }

        
    }

    public function actionKinescope(){
      $dir = '/var/www/site_main/data/www/christmedschool.com/frontend/web/host1/chr';
      $files = File::find()->where(['!=','original_extension','pdf'])->andWhere(['is', 'kinescope_id', new \yii\db\Expression('null')])->all();

      foreach($files as $d){
        $KINESCOPE_API_TOKEN = '33adaa3f-ac36-4c5a-8ecd-19b720bf75af';
        
        $parent_id = PlatformPage::find()->where(['id'=>$d->item_id])->select(['kinescope_folder_id'])->column()[0];
        
        $headers = array(
          'Authorization: Bearer ' . $KINESCOPE_API_TOKEN,	
          'X-Video-Title: '	. $d->original_name,
          'X-Parent-ID: ' . $parent_id,
          'X-Video-URL: https://cdn.christmedschool.com' . $d->dir
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, "https://uploader.kinescope.io/v2/video");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $d->kinescope_data = json_encode($result['data']);
        $d->kinescope_id = $result['data']['id'];
        
        if( $d->save() ) print_r($d->getErrors());

        

      }
    }

    public function actionCheckCountVideo(){
      $pp = PlatformPage::find()->all();

      $arr = [];
      foreach($pp as $i=>$d){

        $regexp_1 = '<source[^>]+src="([^">]+)"';
        $regexp_2 = "{video::(.*)}";

        preg_match_all("/$regexp_1/siU", $d->content_old1, $matches_1, PREG_SET_ORDER);
        preg_match_all("/$regexp_2/siU", $d->content, $matches_2, PREG_SET_ORDER);

        //print_r($matches_1);
        //die();

        //$arr[] = $d;


        if( count($matches_1) != count($matches_2) )  print_r( count($matches_1) .'/'. count($matches_2) );
      }

      die();

      foreach($arr as $i=>$d){

        //$regexp = "<video(.*)src=\"([^\"]*)\"(.*)<\/video>";
        //$regexp = "<a(.*)href=\"([^\"]*)\"(.*)<\/a>";

        $d->content = $d->content_old1;

        if( preg_match_all("/$regexp_1/si", $d->content_old1, $matches, PREG_SET_ORDER) ) {


          foreach($matches as $math_key=>$match){

            $match[1] = urldecode($match[1]);

            $match[1] = str_replace('//','/', str_replace('https://cdn.christmedschool.com','',$match[1]) );
    
            $repl = File::find()->where(['dir'=>$match[1]])->asArray()->one();
            if(!$repl) {
              echo('File not found'.PHP_EOL);
              continue;
            }


            print_r($match[1].' => '.$repl['kinescope_id'].PHP_EOL);

            $d->content = str_replace($match[0],'-->{video::'.$repl['id'].'}<!--',$d->content);
            $d->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $d->content);
            $d->content = preg_replace('#<button(.*?)>(.*?)</button>#is', '', $d->content);
          
          }

          $d->save();

        

      }
    }
    }

    public function actionMemberExport(){


      $numByMember = [];
      $orders = Order::find()->where(['status'=>3])->orderBy(['created'=>SORT_ASC])->all();
      
      foreach($orders as $i=>$d){

        if( !$d->memberId ) continue;

        if( !array_key_exists($d->memberId,$numByMember) ){
          $d->isFirstForMember = 1;
          $d->save();
          $numByMember[$d->memberId] = 1;
        }

        /*if( !array_key_exists($d->memberId,$numByMember) ) $numByMember[$d['memberId']] = [
          'firstOrderDate'=>null,
          'lastOrderDate'=>null,
          'orderCompletedSumTotal'=>0,
          'orderCompletedCountTotal'=>0
        ];

        if( !$numByMember[$d['memberId']]['firstOrderDate'] ) $numByMember[$d['memberId']]['firstOrderDate'] = $d['created'];

        $numByMember[$d['memberId']]['lastOrderDate'] = $d['created'];
        $numByMember[$d['memberId']]['orderCompletedSumTotal'] += $d['amount'];
        $numByMember[$d['memberId']]['orderCompletedCountTotal'] += 1;
        */

      }

      die();

      foreach($numByMember as $i=>$d){
        $ms = new MemberStats;
        $ms->memberId = $i;
        $ms->firstOrderDate = $d['firstOrderDate'];
        $ms->lastOrderDate = $d['lastOrderDate'];
        $ms->orderCompletedSumTotal = $d['orderCompletedSumTotal'];
        $ms->orderCompletedCountTotal = $d['orderCompletedCountTotal'];
        $ms->save();

      }

      die();

      $members = Member::find()->select(['id','email','firstname','phone','birth_date','platform_username','platform_joined','vk_id','vk_firstname','vk_lastname'])->asArray()->all();
      foreach($members as $i=>$m){
        print_r('Processing id '.$m['id'].PHP_EOL);
        if( array_key_exists($m['id'],$numByMember) ){
          foreach($numByMember[ $m['id'] ] as $key=>$val){
            $members[$i][$key] = $val;
          }
        }
        
      }

      

      //file_put_contents('/var/www/site_main/data/www/christmedschool.com/members_export.txt',json_encode($members));
    }

    public function actionProductCharacts(){
      $pages = Product::find()->asArray()->all();

      foreach($pages as $i=>$p){
        if(!$p['content']) continue;
        //if($p['id'] != 1238) continue;

        $c_arr_2 = [];

        $c = preg_replace( "/(<p>.*?<\/p>)/is", '', $p['content'] );

        preg_match_all('/<li class="item-params-list-item(\s.*?)?">(.*?)<\/li>/is',$c,$matches);

        preg_match_all('/<span class="item-params-label(\s.*?)?">(.*?)<\/span>/is',$c,$matches_keys);

        if(!$matches || !$matches_keys) continue;

        foreach($matches_keys[2] as $i=>$d){
          $c_arr_2[ str_replace(':','',trim($d)) ] = trim(strip_tags(str_replace($matches_keys[0][$i],'',$matches[0][$i])));
        }

        if(!$c_arr_2) continue;

        foreach($c_arr_2 as $i=>$d){
          $pc = ProductCharacts::find()->where(['productId'=>$p['id'],'name'=>$i])->one();
          if(!$pc) $pc = new ProductCharacts;
          $pc->productId = $p['id'];
          $pc->name = $i;
          $pc->content = html_entity_decode($d);
          $pc->save();
        }


  
        print_r($c_arr_2);

        //die();
      }

      

    }

    public function actionChtag(){

      /*$product = Product::find()->with(['page','characts'])->asArray()->all();

      $p_ch = [];

      foreach($product as $i=>$d){
        if(!$d['page']) continue;
        if(!$d['characts']) continue;

        $d['page'][0]['id'] = $d['page'][0]['h1'];

        if( !array_key_exists($d['page'][0]['id'],$p_ch) )  $p_ch[$d['page'][0]['id']] = [];

        foreach($d['characts'] as $ch_i=>$ch_d){
          if( !in_array($ch_d['name'], $p_ch[$d['page'][0]['id']]) )  $p_ch[$d['page'][0]['id']][] = $ch_d['name'];
        }
        

      }

      print_r($p_ch);*/


      $tags = ProductCharacts::find()->where(['name'=>'Доступность'])->select(['content'])->column();
      $tags = array_unique($tags);

      foreach($tags as $i=>$d){
        $m = new TagsPreset;
        $m->tag = $d;
        $m->itemType = 'characts_Доступность';
        $m->save();
      }

      
    }
    
		
	}