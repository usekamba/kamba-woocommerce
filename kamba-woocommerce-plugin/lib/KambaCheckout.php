<?php
/**
 * 
 * Restful Kamba Checkout API client
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include "Request.php";
include "ValidationException.php";

Class KambaCheckout {
	private $api_endpoint;
	private $auth_headers;
	private $merchant_id;
	private $api_key;
	private $curl;
	private $test_mode;

	public function phpAlert($msg) {
		//echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}
	
	function __construct($merchant_id, $api_key, $test_mode)
	{
		$this->curl             =   new Request;
		$this->merchant_id 		=   $merchant_id;
		$this->api_key	        =   $api_key;
		$this->test_mode       =   $test_mode;
		
		phpAlert("Init KambaCHeckout");
		
		if($test_mode)
			$this->api_endpoint  = "https://sandbox.usekamba.com/v1/checkouts";
		else
			$this->api_endpoint  = "https://api.usekamba.com/v1/checkouts";
	}
	
	public function createCheckout($data)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->api_endpoint ,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				"authorization: Token $this->api_key",
				"content-type: application/json"
				)
		));

		$response = curl_exec($curl);
		$resp = json_decode($response, true);
		$checkout_url = "";
		$business_name = $resp['merchant']['business_name'];
		$notes = $resp['notes'];
		$total_amount = $resp['total_amount'];
		$timestamp = strtotime($resp['expires_at']);
		$expiration_date = date('d/m/Y H:i:s', $timestamp);
		$checkout_mobile_link = "https://usekamba.page.link/?link=https://www.usekamba.com/&apn=com.usekamba.kamba.kamba&ibi=com.usekamba.kamba&mID=".$resp['id']."&chID=".$resp['id'];
		if ($this->test_mode) {
		    $checkout_url = "https://sandbox.usekamba.com/v1/pay?mID=".$resp['merchant']['id']."&chID=".$resp['id'];
		} else {
		    $checkout_url = "https://api.usekamba.com/v1/pay?mID=".$resp['merchant']['id']."&chID=".$resp['id'];
		}
		phpAlert("Checkout URL $checkout_url");
		setCheckout($checkout_url, $checkout_mobile_link, $business_name, $total_amount, $expiration_date, $notes);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
		echo "cURL Error #:" . $err;
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
        
		if(isset($result->id) and $result->id)
			return $result;
		else
			throw new Exception("Unable to Fetch Payment Request id:'$id' Server Responds ".print_r($result,true));
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
