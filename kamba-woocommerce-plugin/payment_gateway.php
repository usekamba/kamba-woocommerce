<?php

if (!defined( 'ABSPATH' )) {
	exit;
}

function phpAlert($msg) 
{
    //echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

Class WP_Gateway_Kamba extends WC_Payment_Gateway 
{   
	private $testmode;
	private $merchant_id;
	private $secret_key;
	
	public function __construct()
	{
		$this->id                   = "kamba-woocommerce";
		$this->icon                 = "https://raw.githubusercontent.com/usekamba/kamba-checkout-universal-design/master/assets/img/kamba-badge.png";
		$this->has_fields           = false;
		$this->method_title         = "Kamba Checkout";
		$this->method_description   = "Recargas de saldo e pagamentos na Internet com dinheiro";
		$this->init_form_fields();
		$this->init_settings();
		$this->title            = $this->get_option( 'title' );
		$this->description      = "Ao escolher Kamba Checkout como forma de pagamento, você finalizará o seu pagamento com 
		a sua Carteira Kamba instalada em seu telemóvel para escanear o código qr e autorizar o pagamento.";
		$this->testmode         = 'yes' === $this->get_option( 'testmode', 'no' );
		$this->merchant_id      = $this->get_option( 'client_id' );
		$this->secret_key		= $this->get_option( 'secret_key' );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}
	
	public function init_form_fields()
	{
		$this->form_fields = include("kamba-settings.php");		
	}

	public function process_payment($orderId)
	{
		include_once "lib/KambaCheckout.php";
		
		$order = new WC_Order( $orderId );
		$product_list = "";
        $order_item = $order->get_items();

        foreach ($order_item as $product) {
            $product_list .= $product["qty"]." x " .$product['name'];
        }        
		try {
			$request = new KambaCheckout($this->merchant_id, $this->secret_key, $this->testmode);
            $request_data['channel']                = "WEB";
			$request_data['initial_amount'] 	    = $this->get_order_total();
			$request_data['notes'] 		            = $product_list;
			$request_data['redirect_url_success'] 	= site_url()."/kamba-checkout/";

			$response = $request->createCheckout(stripcslashes(json_encode($request_data)));
			if (isset($response)) {	
				$response = json_decode($response, true);
				$url = $response['redirect_url_success'];
				WC()->session->set( 'payment_request_id',  $response->id);	
				return array(
                    'result'    => 'success', 
                    'redirect'  => $url
				);
			}
		} catch (CurlException $e) {
			phpAlert("An error occurred on line " . $e->getLine() . " with message " .  $e->getMessage());
			phpAlert("Traceback: " . (string)$e);
			$json = array(
				"result"    =>  "failure",
				"messages"  =>  "<ul class=\"woocommerce-error\">\n\t\t\t<li>" . $e->getMessage() . "</li>\n\t</ul>\n",
				"refresh"   =>  "false",
				"reload"    =>  "false"
				);
			phpAlert($json);
			die(json_encode($json));
		} catch (ValidationException $e) {
			$errors_html = "<ul class=\"woocommerce-error\">\n\t\t\t";
			foreach ( $e->getErrors() as $error) {
				$errors_html .="<li>".$error."</li>";
			}
			$errors_html .= "</ul>";
			$json = array(
				"result"    =>  "failure",
				"messages"  =>  $errors_html,
				"refresh"   =>  "false",
				"reload"    =>  "false"
				);
			die(json_encode($json));
		} catch (Exception $e) {
			$json = array(
				"result"    =>  "failure",
				"messages"  =>  "<ul class=\"woocommerce-error\">\n\t\t\t<li>".$e->getMessage()."</li>\n\t</ul>\n",
				"refresh"   =>  "false",
				"reload"    =>  "false"
				);
			die(json_encode($json));
		}
	}

	public static function log( $message, $level = 'info' ) {
        if ( self::$log_enabled ) {
            if ( empty( self::$log ) ) {
                self::$log = wc_get_logger();
            }
            self::$log->log( $level, $message, array( 'source' => 'Kamba Checkout' ) );
        }
    }
}