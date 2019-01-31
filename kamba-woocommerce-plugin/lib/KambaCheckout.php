<?php
/**
 * 
 * Restful Kamba Checkout API client
 * 
 */

date_default_timezone_set("Africa/Luanda");

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include "Request.php";
include "ValidationException.php";

Class KambaCheckout {
	private $api_endpoint;
	private $auth_headers;
	private $merchant_id;
	private $curl;
	private $test_mode;
	private $time_stamp;
	private $merchant_secret;
	private $secret_key;

	public function phpAlert($msg) {
		$this->write_log($msg);
	}

	public function write_log ( $log )  {
		   if ( is_array( $log ) || is_object( $log ) ) {
			  error_log( print_r( $log, true ) );
		   } else {
			  error_log( $log );
		   }
	}
	
	
	function __construct($merchant_id, $secret_key, $test_mode)
	{
		$this->curl             =   new Request;
		$this->merchant_id 		=   $merchant_id;
		$this->test_mode        =   $test_mode;
		$this->merchant_secret  =   $secret_key;
		
		phpAlert("Init KambaCHeckout");
		if ($this->test_mode)
			$this->api_endpoint  = "https://sandbox.usekamba.com/v1/checkouts";
		else
			$this->api_endpoint  = "https://api.usekamba.com/v1/checkouts";
	}

	public function generateConanicalString($data) {
		phpAlert($data);
		$this->time_stamp = gmstrftime ("%a, %d %b %Y %T %Z", time());
		$this->write_log(" checkout body => POST,application/json,".md5($data).",/v1/checkouts,".$this->time_stamp);
		return "POST,application/json,".md5($data).",/v1/checkouts,".$this->time_stamp;
	}

	public function generateHMAC($conanical_string, $merchant_secret) {
		$hmac_signature = hash_hmac("sha1", $conanical_string, $merchant_secret);
		$this->write_log("hmac: ".$hmac_signature);
		$this->write_log(" Signature: $this->time_stamp.".base64_encode($hmac_signature));
		return base64_encode($hmac_signature);
	}
	
	public function createCheckout($data)
	{
		$curl = curl_init();
		$conanical_string = $this->generateConanicalString($data);
		$hmac_signature = $this->generateHMAC($conanical_string, $this->merchant_secret);
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->api_endpoint ,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				"Merchant-ID: $this->merchant_id",
				"Signature: $this->time_stamp.$hmac_signature",
				"Content-type: application/json"
				)
		));

		$info = curl_getinfo($curl);

		$response = curl_exec($curl);
		$resp = json_decode($response, true);
		$checkout_url = "";
		$business_name = $resp["merchant"]['business_name'];
		$notes = $resp['notes'];
		$total_amount = $resp['total_amount'];
		$timestamp = strtotime($resp['expires_at']);
		$expiration_date = date('d/m/Y H:i:s', $timestamp);
		$checkout_mobile_link = "https://comerciante.usekamba.com/pay?chID=".$resp['id'];
		if ($this->test_mode) {
		    $checkout_url = "https://comerciante.usekamba.com/pay?chID=".$resp['id'];
		} else {
		    $checkout_url = "https://comerciante.usekamba.com/pay?chID=".$resp['id'];
		}
		$this->write_log($checkout_url);
		configCheckout($checkout_url, $checkout_mobile_link, $business_name, $total_amount, $expiration_date, $notes);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		echo "cURL Error#:" . $err;
		} else {
			phpAlert("No error");
		}
		
		if (isset($response)) {
			phpAlert("Response is valid");
			return $response;
		} else {
			$errors = array();  
			phpAlert("errors: $errors");
			foreach ($response as $key => $value) {
				if (is_array($value))
					$errors[] = $value[1];
			}
			if ($errors)
				throw new ValidationException("Validation Error Occured with following Errors : ",$errors, $response);
		}
	}
	
	public function getCheckoutById($id)
	{	phpAlert("GetCheckoutById");
		$endpoint   =   $this->api_endpoint."checkouts/$id";
		$result     =   $this->curl->get($endpoint,array("headers"=>$this->auth_headers));
        $result     =   json_decode($result);
        
		if (isset($result->id) and $result->id)
			return $result;
		else
			throw new Exception("Unable to Fetch Payment Request id:'$id' Server Responds ".print_r($result, true));
	}

	public function getCheckoutStatus($checkout_id, $checkout){
		phpAlert("getCheckoutStatus");
		foreach($checkouts as $checkout){
		    if ($checkout->id == $checkout_id) {
			    return $checkout->status;
		    }
		}
	}
}