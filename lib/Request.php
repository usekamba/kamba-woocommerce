<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include dirname(__FILE__). DIRECTORY_SEPARATOR ."RequestException.php";

class Request
{
	private $ch;
	private $cookie_file;
	private $useragent;
	private $referer;
	private $showRequestHeaders;
	private $showResponseHeaders;
	private $debug;
	private $info;
	private $url;
	private $data;
	private $requestMethod;
	private $headers;
	
	function __construct()
	{
		 $this->ch = curl_init();
		 $this->cookie_file = dirname(__FILE__)."/cookie.txt";
		 $this->useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36";
	}
	
	public function debug($bool)
	{
		$this->debug = $bool;
    }
    
	public function responseHeaders($enable)
	{
		$this->showRequestHeaders = $enable;
    }
	
	public function requestHeaders($enable)
	{		
		$this->showResponseHeaders = $enable;
 	}
	
	public function setUserAgent($ua)
	{
		$this->useragent = $ua;
	}
	
	public function setReferer($referer)
	{
		$this->referer = $referer;
		curl_setopt($this->ch, CURLOPT_REFERER  , $this->referrer);
		
	}

	private function prepare($url,$options)
	{
		curl_close($this->ch);
		
		$this->ch = curl_init();
		
		if(!$url)
			throw new Exception("The url is not provided");
        $this->url = $url;
        
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER , 1);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_COOKIESESSION, true );
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->useragent );
		
		if($this->debug)
		{
			$f = fopen("request.txt","a");
			curl_setopt($this->ch, CURLOPT_VERBOSE , 1 );
		}	
		
		if($this->showRequestHeaders)
			curl_setopt($this->ch, CURLINFO_HEADER_OUT, 1 );
		
		if($this->showResponseHeaders)
			curl_setopt($this->ch, CURLOPT_HEADER, 1 );

		if(isset($options['headers'])){
			$this->headers = $options['headers'];
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $options['headers'] );
			
		}
		
		if(isset($options['referer']))
			curl_setopt($this->ch, CURLOPT_REFERER, $options['referer']);
			
	}
	
	private function execute()
	{
		$requestData = curl_exec($this->ch);

		$error_number = curl_errno($this->ch);
        $error_message = curl_error($this->ch);

        $this->errorMessage = "cURL Error Number: $error_number. Error message: $error_message.";

        if ($error_number == 7){
        	$this->errorMessage = "cURL Error Number: $error_number. Error message: $error_message.<a/>";
        }
		
		$this->responseCode = curl_getinfo($this->ch)['http_code'];

		if(!$requestData)
			throw new CurlException($this->errorMessage, $this);

		if($error_number != 0){
			throw new CurlException($this->errorMessage, $this);
		}
		return $requestData;
		
    }
    
	public function get($url,$options= array())
	{
		$this->url =  "";
		$this->requestMethod =  "";
		$this->data = "";
		$this->headers = "";
		
		$this->prepare($url,$options);
		$this->requestMethod ="GET";
		return $this->execute();
		
	}
 	
	public function post($url,$data,$options= array())
	{
		$this->url =  "";
		$this->requestMethod =  "";
		$this->data = "";
		$this->headers = "";
		
		$this->data = $data;
		$this->requestMethod ="POST";
		$this->prepare($url,$options);

		curl_setopt( $this->ch, CURLOPT_POST, 1 );
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $data );

		return $this->execute();
	}
	
	public function __destruct()
	{
		curl_close($this->ch);
	}
	
	public function __toString(){
		return "Requesting  '$this->url' url using  '$this->requestMethod' method".PHP_EOL .
				"and Data:".print_r($this->data,true).PHP_EOL .
				"Headers are : ".print_r($this->headers,true).PHP_EOL .
				"ErrorMessage(if any) : " . $this->errorMessage. PHP_EOL .
				"with Response Code:".$this->responseCode;
		
	}
}
