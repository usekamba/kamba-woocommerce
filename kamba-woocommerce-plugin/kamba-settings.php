<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', 'woocommerce' ),
				'type' => 'checkbox',
				'label' => __( 'Habilitar Kamba Checkout', 'kamba' ),
				'default' => 'yes'
			),
			'title' => array(
				'title' => __( 'Title*', 'woocommerce' ),
				'type' => 'text',
				'description' => __( 'Descrição que o usuário vê durante o checkout.', 'woocommerce' ),
				'default' => __( 'Kamba', 'woocommerce' ),
				'desc_tip'      => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => __( 'Descrição que o usuário vê durante o checkout.', 'woocommerce' ),
				'default'     => __( 'Pagar com Kamba Checkout', 'kamba' )
			),
			'api_details' => array(
				'title'       => __( 'Chaves de API', 'kamba' ),
				'type'        => 'title',
				'description' => '',
			),
			'client_id' => array(
				'title' => __( 'Merchant ID*', 'kamba' ),
				'type' => 'text',
				'description' => __( 'O seu Merchant ID está disponível na sua conta comerciante, no menu Integrações', 'instamojo' ),
				'desc_tip'      => true,
			),
			'client_secret' => array(
				'title' => __( 'Chave de API*', 'woocommerce' ),
				'type' => 'text',
				'description' => __( 'A sua chave de API está disponível na sua conta comerciante, no menu Integrações', 'woocommerce' ),
				'desc_tip'      => true,
			),
			'testmode' => array(
				'title'       => __( 'Modo Sandbox', 'kamba' ),
				'type'        => 'checkbox',
				'label'       => __( 'Habilitar modo Sandbox', 'kamba' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Para habilitar o modo Sandbox crie uma conta Comerciante de testes em  %s', 'woocommerce' ), 'https://comerciante.usekamba.com/' ),
			),
			'checkout_url' => array(
				'title' => __( 'URL do checkout*', 'woocommerce' ),
				'type' => 'text',
				'description' => __('Url que irá apresentar o código qr do checkout', 'woocommerce' ),
				'desc_tip'      => '',
			),		
		);