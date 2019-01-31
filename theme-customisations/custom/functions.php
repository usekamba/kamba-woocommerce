<?php
/**
 * Functions.php
 *
 * @package  Theme_Customisations
 * @author   WooThemes
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * functions.php
 * Add PHP snippets here
 */

add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
function bbloomer_redirectcustom( $order_id ){
	$order = new WC_Order( $order_id );
		
	$url = 'http://yoursite.com/custom-url';
		
	if ( $order->status != 'failed' ) {
		wp_redirect($url);
		exit;
	}
}


if ( ! function_exists('write_log')) {
	function write_log ( $log )  {
	   if ( is_array( $log ) || is_object( $log ) ) {
		  error_log( print_r( $log, true ) );
	   } else {
		  error_log( $log );
	   }
	}
 }

// This code should not be deleted or altered
//add_action('init', 'configCheckout');
function configCheckout($checkout_data, $checkout_mobile, $business_name, $total_amount, $expiration_date, $notes) {
	setcookie("checkout", $checkout_data, time() + 3600, "/");
	setcookie("mobile_checkout", $checkout_mobile, time() + 3600, "/");
	setcookie("business_name", $business_name, time() + 3600, "/");
	setcookie("total_amount", $total_amount, time() + 3600, "/");
	setcookie("expiration_date", $expiration_date, time() + 3600, "/");
	setcookie("notes", $notes, time() + 3600, "/");
}
