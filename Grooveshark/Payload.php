<?php

namespace Grooveshark;

class Payload {

 	public $url = 'https://api.grooveshark.com/ws3.php';
	public $key;
	public $secret;
	public $httpHeaders;
	public $sessionID;

	public function __construct($args = array()){
		if(!empty($args['key'])){
		   $this->key = $args['key'];
		}

		if(!empty($args['secret'])){
			$this->secret = $args['secret'];
		}
		
		if (empty($this->key) || empty($this->secret)){
			die('Key or secret is missing');
		}
	}

	public function request($method, $args = array()){
		$payload = array('method'=>$method, 'parameters'=>$args, 'header'=> $this->getHeader());
		
		$postData = json_encode($payload);
		$signature = $this->_createSignature($postData, $this->secret);
		$query_str = "?sig=" . $signature;
		$full_url = $this->url.$query_str;

 		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $full_url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 4);
		if (!empty($this->httpHeaders)) {
			curl_setopt($c, CURLOPT_HTTPHEADER, $this->httpHeaders);
		}
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($c, CURLOPT_TIMEOUT, 10);
		curl_setopt($c, CURLOPT_USERAGENT, 'ckdarby/Grooveshark-API-PHP');
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c, CURLOPT_POSTFIELDS, $postData);
		$result = curl_exec($c);
		$httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		$decoded = json_decode($result, true);
		$result = array('http'=>$httpCode,'raw'=>$result,'decoded'=>$decoded);
		
		return $result;
	}

	private function _createSignature($args, $secret){
	    return hash_hmac('md5', $args, $secret);
	}

	public function getHeader(){
		$header = array('wsKey'=>$this->key);
		if (isset($this->sessionID)){
			$header['sessionID'] = $this->sessionID;
		}

		return $header;
	}

}
