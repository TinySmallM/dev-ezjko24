<?php

namespace common\models;

use Yii;
use \morphos\Russian\GeographicalNamesInflection;
use \morphos\Russian\pluralize;
use yii\helpers\ArrayHelper;

use common\models\Template;
use common\models\TemplateBlock;
use common\models\ActionConfirm;

class Helpers
{
	
	/*
	public function appGlobals(){
		$tpl = Template::find()->asArray()->all();
		foreach($tpl as $t_i=>$t_d){
			$block = TemplateBlock::find()->where(['templateId'=>$t_d['id']])->asArray()->all();
			foreach($block as $b_i=>$b_d){
				$block['chunk'] = json_decode($block['chunk']);
			}
			
			$tpl[$t_i]['block'] = $block;
		}
		Yii::$app->view->params['template'] = $tpl;
	}
	*/

	public static function randStr($length){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	
	public static function currentRegion(){
		//$domain = yii::$app->request->hostName;
		//if( !array_key_exists($domain,Yii::$app->params['region']) ) Yii::$app->params['region']['current'] = Yii::$app->params['region'][0];
		//else Yii::$app->params['region']['current'] = Yii::$app->params['region'][$domain];
	}
	
	public static function phReplace($str){

		$plurs = [
			'им'=>'именительный',
			'род'=>'родительный',
			'дат'=>'дательный',
			'вин'=>'винительный',
			'твор'=>'творительный',
			'пред'=>'предложный'
		];
		
		preg_match_all('/\{.*?}/',$str,$arr);
		if($arr[0]){
		    foreach($arr[0] as $item){
		        $key = str_replace(['}','{'],'',$item);
		        
		        //Заменяем падеж города или области
		        if( strpos($key,'city|') > -1 || strpos($key,'oblast|') > -1 ){
		        	
		        	$keyArr = explode('|',$key);
		        	if( array_key_exists($keyArr[1],$plurs) ){
		        		$str = str_replace($item,GeographicalNamesInflection::getCase(Yii::$app->params['region']['current'][$keyArr[0]], $plurs[$keyArr[1]]),$str);
		        	}
		        	
		        } 
		        
		        //Заменяем все остальное
		        if(isset(yii::$app->params['domain'][$key])){
		        	$str = str_replace($item,yii::$app->params['region']['current'][$key],$str);
		        }
		    }
		}
		return $str;
	}

	public static function getPlur($num,$str){
		if(!$num && $num != 0) return $str;
		else if( ($num == 0 || $num == 5)  && $str = 'спальня') return $num.' спален';
		else return \morphos\Russian\pluralize($num, $str);
	}

	public static function processChunk($items,$templateId){
		$items = ArrayHelper::index($items, 'name');
		$chunks = Yii::$app->params['template'][$templateId]['chunk'];
		foreach($chunks as $ch_i=>$ch_d){
			if( !array_key_exists($ch_i,$items) ) {
				$items[$ch_i]['content'] = $ch_d['default'];
				$items[$ch_i]['id'] = null;
			}
			
			$items[$ch_i]['name'] = $ch_d['name'];
			$items[$ch_i]['type'] = $ch_d['type'];
			
			if( array_key_exists('json',$ch_d) ) $items[$ch_i]['content'] = json_decode($items[$ch_i]['content'], 1);
		}	
		
		return $items;
	}

	public static function translit($s)
    {
        $s = (string) $s;
        $s = strip_tags($s);
        $s = str_replace(array("\n", "\r"), " ", $s);
        $s = preg_replace("/\s+/", ' ', $s);
        $s = trim($s);
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
        $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'eh', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s);
        $s = str_replace(" ", "-", $s);
        return $s;
    }

	public static function checkDevice() {
	// checkDevice() : checks if user device is phone, tablet, or desktop
	// RETURNS 0 for desktop, 1 for mobile, 2 for tablets
	
		if (is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"))) {
			return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet")) ? 'tablet' : 'mobile' ;
		} else {
			return 'desktop';
		}
	}

	private static function HTTPPost($url, array $params) {
        $query = json_encode($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, 1);
    }

    public static function generatePlatformCode ( $fio,$email,$ids ){
        //$order = wc_get_order( $id );
        //$data = $order->get_data(); // данные заказа
       // $order_items = $order->get_items();
        $url = 'https://school.christmedschool.com/api/ajksdakjsdkaskdkaklsdlkaskqugwy1728yhksdfvsghjdaksgvvgsahjkflasdhasd';
        $auth_token = 'iiuhqwgeh12736yuewhdsjbddheuiok#$%^&*(JKHGFDERW$%^&YUHJVCFDSWAQ#@$%^TYGH';
        $items_data_array = array();
        
        $send_data = array(
            'first_name' => $fio,
            'email' => $email,
            'items_articles' => $ids,
            'auth_token' => $auth_token
        );
        $response = Helpers::HTTPPost($url, $send_data);
        return $response;
    }


	public static function replaceByItem($text,$item){

		if(!$item) return $text;
		
		foreach($item as $key=>$val){
			if(!$val) continue;
			if($key == 'chunk'){
				foreach($val as $ch_i=>$ch_d){
					$text = str_replace('{{chunk.'.$ch_i.'}}',$ch_d['content'],$text);
				}
			}
			else $text = str_replace('{{'.$key.'}}',$val,$text);
		}

		return $text;

	}

	function phone_format_numbers($phone) 
	{
		$phone = trim($phone);
	
		$res = preg_replace(
			array(
				'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
				'/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
				'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
				'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',	
				'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
				'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',					
			), 
			array(
				'7$2$3$4$5', 
				'7$2$3$4$5', 
				'7$2$3$4$5', 
				'7$2$3$4$5', 	
				'7$2$3$4', 
				'7$2$3$4', 
			), 
			$phone
		);
	
		return $res;
	}

	public static function sendCallConfirm($phone,$clientIp=null){
		$ch = curl_init("https://sms.ru/code/call");

		$data = [
			"phone" => $phone, // номер телефона пользователя
			"api_id" => "87840FD5-E8BD-6968-3FF0-F01E7B68E881"
		];

		//if($clientIp) $data['ip'] = $clientIp;

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) );
		$body = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($body,1);
		if ($json) { // Получен ответ от сервера
			return $json;
		} else { 
			return ['error'=>'Невозможно отправить запрос.'];
		}
	}

	public static function sendCallConfirmMcn($phone){

		$code = intval(substr(str_shuffle("123456789"), 0, 4));
	

		$ch = curl_init("https://paidmethods.mcn.ru/api/protected/api/auth/passwordcall");

		

		$data = [
			"phone_number" => $phone, // номер телефона пользователя
			"lang" => "ru_RU",
			"is_with_redirect" => false,
			"verification_code" => $code
		];

		//return $data;

		//if($clientIp) $data['ip'] = $clientIp;

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer 75de062de99396d163d0c739879c0430c0e8d534d5c65f2d',
			'Content-Type: application/json'
		]);
		$body = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($body,1);

		if ($json) { // Получен ответ от сервера
			return ['verification_code'=>$code,'data'=>$json,'type'=>'phone_call'];
		} else { 
			return ['error'=>'Невозможно отправить запрос.'];
		}
	}

	public static function sendAuthMsgMcn($phone){

		$code = intval(substr(str_shuffle("123456789"), 0, 4));
	

		$ch = curl_init("https://a2p-sms-api.mcn.ru/api/a2p_sms/api/v1.1/send_sms");

		$data = [
			"receiver" => $phone, // номер телефона пользователя
			"msgdata" => "Ваш код: ".$code,
			"title" => "send_sms",
			"sender" => "MCNtelecom"
		];

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer 75de062de99396d163d0c739879c0430c0e8d534d5c65f2d',
			'Content-Type: application/json'
		]);
		$body = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($body,1);

		if ($json) { // Получен ответ от сервера
			return ['verification_code'=>$code,'data'=>$json,'type'=>'sms'];
		} else { 
			return ['error'=>'Невозможно отправить запрос.'];
		}
	}

	public static function sendMsgMcn($phone,$text){	

		$ch = curl_init("https://a2p-sms-api.mcn.ru/api/a2p_sms/api/v1.1/send_sms");

		$data = [
			"receiver" => $phone, // номер телефона пользователя
			"msgdata" => $text,
			"title" => "send_sms",
			"sender" => "MCNtelecom"
		];

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer 75de062de99396d163d0c739879c0430c0e8d534d5c65f2d',
			'Content-Type: application/json'
		]);
		$body = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($body,1);

		if ($json) { // Получен ответ от сервера
			return $json;
		} else { 
			return false;
		}
	}

	public static function checkActionLimit($type,$item_confirmed){
		if($type == 'sms'){
			$check_1_mins = ActionConfirm::find()->where(['item_confirmed'=>$item_confirmed])
				->andWhere(['>=','dateCreated',intval(date('U'))-60 ])
				->count();
			if( $check_1_mins > 0) return ['error' => 'Мы уже отправили СМС с кодом. Пожалуйста, попробуйте еще раз через 1 минуту.']; 

			$check_5_mins = ActionConfirm::find()->where(['item_confirmed'=>$item_confirmed])
				->andWhere(['>=','dateCreated',intval(date('U'))-180 ])
				->count();
			if( $check_5_mins > 3) return ['error' => 'Превышено количество попыток отправить СМС в 1 час. Попробуйте еще раз через 5 минут.']; 

			$check_180_mins = ActionConfirm::find()->where(['item_confirmed'=>$item_confirmed])
				->andWhere(['>=','dateCreated',intval(date('U'))-10800 ])
				->count();
			if( $check_180_mins > 10) return ['error' => 'Превышено количество попыток отправить СМС в 3 часа. Попробуйте еще раз через 180 минут.']; 

			return true;

		}
	}

	public function arrayToXlsx($data){
        	
    	$alphabet_old = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $alphabet = $alphabet_old;
        foreach($alphabet_old as $d){

            foreach($alphabet_old as $k){
                $alphabet[] = $d.$k;
            }
        }
		
    	$keys = array_keys($data[0]);
    	
    	$head = [];

		foreach($keys as $key_i=>$key_name){
			$head[$key_name] = ['name'=>$key_name,'xPos'=>$alphabet[$key_i]];
		}
    	
    	$spreadsheet = new Spreadsheet();
    	$sheet = $spreadsheet->getActiveSheet();
    	
    	foreach($head as $k=>$d){
    		$sheet->getColumnDimension($d['xPos'])->setAutoSize(false);
			$sheet->setCellValue($d['xPos'].'1',$d['name']);
		}
		
		/* Рисуем тело таблицы */
		$numY=2;$numX=1;
		foreach($data as $item_key=>$item){
			foreach($keys as $key_i=>$key_name){
				$sheet->setCellValue($head[$key_name]['xPos'].($numY), !empty($item[$key_name])?$item[$key_name]:'');
			}
		    
			$numY = $numY+1;
		}

        $fileName = 'file_export_'.md5(date('Y-m-d h:i:s')).'.xlsx';
		
    	
    	/* Сохраняем и возвращаем ссылку */
		file_put_contents(Yii::$app->basePath.'/../backend/web/export/'.$fileName,'');
    	$writer = new Xlsx($spreadsheet);
		$writer->save(Yii::$app->basePath.'/../backend/web/export/'.$fileName);	

        return '/master/export/'.$fileName;
    }

	public function unsubscribeLink($email){
		return 'https://christmedschool.com/lk/unsubscribe?email='.$email.'&code='.md5($email.'m4kofm3fklsdmfmsff#');
	}


}