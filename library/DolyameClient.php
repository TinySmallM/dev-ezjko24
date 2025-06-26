<?php

namespace Dolyame\Payment;

class DolyameClient
{
	protected $url = 'https://partner.dolyame.ru/v1/orders/';

	protected $login    = '';
	protected $password = '';
	protected $certPath = '';
	protected $keyPath  = '';
	protected $certPass = '';
	protected $logger   = false;

	public function __construct(string $login, string $password)
	{
		$this->login    = $login;
		$this->password = $password;
	}

	public function setCertPath(string $certPath)
	{
		$this->certPath = $certPath;
	}

	public function setKeyPath(string $keyPath)
	{
		$this->keyPath = $keyPath;
	}

	public function setCertPass(string $certPass)
	{
		$this->certPass = $certPass;
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function generateCorrelationId()
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0x0fff) | 0x4000,
			mt_rand(0, 0x3fff) | 0x8000,
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	public function create(array $data, string $correlationId = '')
	{
		$data['order']['id'] = self::prepareOrderId($data['order']['id']);
		return $this->execute('create', $data, 'POST', $correlationId);
	}

	public function cancel(string $orderId, string $correlationId = '')
	{
		$orderId = self::prepareOrderId($orderId);
		return $this->execute($orderId . '/cancel', [], 'POST', $correlationId);
	}

	public function commit(string $orderId, array $data, string $correlationId = '')
	{
		$orderId = self::prepareOrderId($orderId);
		return $this->execute($orderId . '/commit', $data, 'POST', $correlationId);
	}

	public function info(string $orderId, string $correlationId = '')
	{
		$orderId = self::prepareOrderId($orderId);
		return $this->execute($orderId . '/info', [], 'GET', $correlationId);
	}

	public function refund(string $orderId, array $data, string $correlationId = '')
	{
		$orderId = self::prepareOrderId($orderId);
		return $this->execute($orderId . '/refund', $data, 'POST', $correlationId);
	}

	protected function execute(string $action, array $data, string $method, string $correlationId)
	{
		if ($correlationId === '') {
			$correlationId = $this->generateCorrelationId();
		}

		$headers = [
			"Content-Type: application/json",
			"X-Correlation-ID: ". $correlationId,
			"Authorization: Basic ". base64_encode("{$this->login}:{$this->password}")
		];

		$responseHeaders = '';
		if (!function_exists("curl_init")) {
			throw new \Exception("Curl error");
		}

		$request = curl_init();
		$url     = $this->url . $action;
		$params  = [
			'headers'   => $headers,
			'method'    => $method,
		];
		if ($this->certPath) {
			if (!file_exists($this->certPath)) {
				throw new \Exception('Cert path did\'t exist: '.$this->certPath);
			}
			if (!file_exists($this->keyPath)) {
				throw new \Exception('Key path did\'t exist: '.$this->keyPath);
			}
			if (!is_readable($this->certPath)) {
				throw new \Exception('Can\'t read cert file: '.$this->certPath);
			}

			if (!is_readable($this->keyPath)) {
				throw new \Exception('Can\'t read key file: '.$this->keyPath);
			}

			$this->addCurlCert($request);

		}

		if (!empty($data) || $method == 'POST') {
			$encodedData    = $this->encode($data);
			$params['body'] = $encodedData;
		}

		curl_setopt($request, CURLOPT_URL,$url);
		curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($request, CURLOPT_HEADER, 1);
		//curl_setopt($request, CURLOPT_USERPWD, $this->login . ":" . $this->password);
		
		//curl_setopt($request, CURLOPT_USERPWD, $username . ":" . $password);
		
		curl_setopt($request, CURLOPT_POST, 1);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_POSTFIELDS,$params['body']);
		

		$result = curl_exec($request);

		$httpcode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		return json_decode($result);
		curl_close($request);

		return $httpcode;

		

		if ( empty($result) ) {
			throw new \Exception('Request error: '.$result->get_error_message());
		}

		$code = $result['response']['code'];

		return $code;

		if ($this->logger) {
			$context = array( 'source' => 'dolyame-payment' );
			$this->logger->info('url' . ' = ' . $url, $context);
			unset($params['headers']['Authorization']);
			$this->logger->info('request' . ' = ' . json_encode($params), $context);
			$this->logger->info('response' . ' = ' . $result['body'], $context);
		}

		$response = json_decode($result['body'], true);
		if ($code == 200) {
			return $response;
		} elseif ($code == 429) {
			sleep($result['headers']['X-Retry-After']);
			return $this->execute($action, $data, $method, $correlationId);
		}

		$error = 'Error: ' . $code;

		if (isset($response['type']) && $response['type'] == 'error') {
			$error .= ' ' . $response['description'];
		}
		if (isset($response['message'])) {
			$error .= ' ' . $response['message'];
		}
		if (!empty($response['details'])) {
			$list = array_map(
				function ($key, $value) {return "$key - $value";},
				array_keys($response['details']),
				array_values($response['details'])
			);
			$error .= ': ' . implode($list);
		}

		if (!$response) {
			$error .= $result['body'];
		}
		throw new \Exception($error, $code);
	}

	private function addCurlCert($ch)
	{
		curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
		curl_setopt($ch, CURLOPT_SSLKEY, $this->keyPath);
	}

	protected function encode(array $data)
	{
		$result = json_encode($data);
		$error  = json_last_error();
		if ($error != JSON_ERROR_NONE) {
			throw new \Exception('JSON Error: '.json_last_error_msg());
		}
		return $result;
	}

	public static function prepareOrderId(string $orderId)
	{
		$orderId = str_replace(['/', '#', '?', '|', ' '], ['-'], $orderId);
		return $orderId;
	}

}
