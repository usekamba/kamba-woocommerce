<?php


if (!defined('ABSPATH')) {
	exit;
}

function logd($message) {
	$log = new WC_Logger();
	$log->add('kamba', $message);
}

logd("Callback Called with payment ID: $payment_id and payment req id: $payment_request_id");

if(!isset($payment_id) or !isset($payment_request_id))
{
	logd("Callback Called without  payment ID or payment req id exittng..");
	wp_redirect(get_site_url());
}

$stored_payment_req_id = WC()->session->get( 'payment_request_id');

if($stored_payment_req_id!= $payment_request_id)
{
	logd("Given Payment request id not matched with  stored payment request id: $stored_payment_req_id ");
	wp_redirect(get_site_url());
}

include_once "lib/KambaCheckout.php";


try{
	$kambaCheckout  = new WP_Gateway_Kamba();
	$testmode       = 'yes' === $kambaCheckout->get_option('testmode', 'no');
	$merchant_id    = $kambaCheckout->get_option('client_id');
	$api_key        = $kambaCheckout->get_option('client_secret');
	logd("Client ID: $merchant_id | Client Secret: $api_key  | Testmode: $testmode ");
	
	$api        =   new KambaCheckout($merchant_id, $api_key, $testmode);
    $response   =   $api->getCheckoutById($payment_request_id);
    
	logd("Response from server: ".print_r($response,true));
	
	$payment_status = $api->getPaymentStatus($payment_id, $response->payments);

	logd("Payment status for $payment_id is $payment_status");

	if($payment_status === "successful" OR  $payment_status =="failed" )
	{
		
		$order_id = $response->transaction_id;
		$order_id = explode("-",$order_id);
		$order_id = $order_id[1];
		logd("Extracted order id from trasaction_id: ".$order_id);
		$order = new WC_Order( $order_id );
			
		if($order)
		{
			if($payment_status == "successful"){
			  logd("Payment for $payment_id was credited.");
			  $order->payment_complete($payment_id);
			  wp_safe_redirect($pgInstamojo->get_return_url($order));
			}
			else if($payment_status == "failed"){
			  logd("Payment for $payment_id failed.");
			  $order->cancel_order( __( 'Unpaid order cancelled - Kamba Checkout Returned Failed Status for payment Id '.$payment_id, 'woocommerce' ));
			  global $woocommerce;
			  wp_safe_redirect($woocommerce->cart->get_cart_url()); 
			}
				
		}else
			logd("Order not found with order id $order_id");
	}else
		logd("Unknown Payment Status");	
	
}catch(CurlException $e){
		logd((String)$e);
}catch(ValidationException $e){
		logd("Validation Exception Occured with response ".print_r($e->getResponse(),true));
}catch(Exception $e){
	logd( $e->getMessage());	
}		
