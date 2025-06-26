<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;
use common\models\User;

class TelegramApi extends Model {
	
    public $botToken = '7515733249:AAGLPKWPpfXfdbwaolz4Ebi6xVp3jtZ_YZE';
    public $data;
    public $result;
    public $user_id;
    public $text;
    public $user;
    public $chat_id;
    public $user_message;
    public $phone;
    public $hook;

    public function sendMessage(){
		$botToken= $this->botToken;
		$website="https://api.telegram.org/bot".$botToken;
		$chatId=$this->chat_id ? $this->chat_id : $this->user_id;  //** ===>>>NOTE: this chatId MUST be the chat_id of a person, NOT another bot chatId !!!**
		$params=[
			'chat_id'=>$chatId,
			'text'=>$this->text,
			'parse_mode' => 'html',
		    'disable_web_page_preview' => true,
		];
		$ch = curl_init($website . '/sendMessage');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);

    	/*$client = new Client(['baseUrl' => 'https://api.telegram.org/']);
    	$token = $this->botToken;
		$response = $client->createRequest()
		    ->setFormat(Client::FORMAT_JSON)
		    ->setUrl("bot$token/sendMessage")
		    ->setData([
		        'chat_id' => $this->chat_id ? $this->chat_id : $this->user_id,
		        'text' => $this->text,
		        'parse_mode' => 'html',
		        //'parse_mode' => htmlentities($this->text),
		        'disable_web_page_preview' => true,
		    ])
			->setOptions([
				'sslallow_self_signed' => true,
				'sslverify_peer_name'     => false,
				])
		    ->send();
	    $this->result = json_decode($response->content, 0);*/
    }

}