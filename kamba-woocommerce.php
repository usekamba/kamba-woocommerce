<?php

	/**
	 * Plugin Name: Kamba WooCommerce
	 * Plugin URI: http://woocommerce.com/products/kamba-woocommerce/
	 * Description: Plugin de integração da API KambaCheckout para WooCommerce.
	 * Version: 1.0.0
	 * Author: UseKamba
	 * Author URI: http://usekamba.com/
	 * Author E-mail: suporte@usekamba.com
	 * Developer: Nelson Mfinda
	 * Developer URI: http://nelsonmfinda.github.io/
	 * Text Domain: kamba-woocommerce
	 *
	 * WC requires at least: 2.2
	 * WC tested up to: 2.3
	 *
	 * License: GNU General Public License v3.0
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
	 */

	/**
	 * Exit if accessed directly.
	 */

	if (!defined('ABSPATH')) {
		exit;
	}

	// Add settings link on plugin page
	function your_plugin_settings_link($links) { 
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=kamba-woocommerce">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}

	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

	function woocommerce_required_admin_notice() {
		echo   '<div class="updated error notice"><p>';
			echo    _e( '<b>Kamba Woocommerce</b> É necessário instalar o WooCommerce primeiro!', 'my-text-domain' ); 
		echo  '</p></div>';
	}

	#check if woocommerce installed.
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'admin_notices', 'woocommerce_required_admin_notice' ); } else
	{
		function kamba_log($message){
			$log = new WC_Logger();
			$log->add( 'kamba', $message );
			echo '<script type="text/javascript">alert("' . $message . '")</script>';
		
		}
		# register our GET variables
		function add_query_vars_filter( $vars ){
		$vars[] = "payment_id";
		$vars[] = "id";
		return $vars;
		}
		add_filter( 'query_vars', 'add_query_vars_filter' );

		# initialize your Gateway Class
		add_action( 'plugins_loaded', 'init_kamba_gateway' );
		function init_kamba_gateway()
		{
			include "payment_gateway.php";
		}

		# look for redirect from instamojo.
		add_action( 'template_redirect', 'init_kamba_payment_gateway' );
		function init_kamba_payment_gateway(){
			if(get_query_var("payment_id") and get_query_var("id")){
				$payment_id = get_query_var("payment_id");
				$payment_request_id = get_query_var("id");
				include_once "payment_confirm.php";
			}
		}

		# add paymetnt method to payment gateway list
		add_filter("woocommerce_payment_gateways","add_kamba");
		function add_kamba($methods){
			$methods[] = 'WP_Gateway_Kamba';
			return $methods;
		}
	
	}

?>
