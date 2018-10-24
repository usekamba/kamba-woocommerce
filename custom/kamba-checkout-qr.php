<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Kamba Checkout QR
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="scantopay" 
	style="width: 380px;
    padding: 1%;
	text-align: center;
	color: white;
	border-radius: 10px;
	-webkit-box-shadow: 0px 1px 6px 0px rgba(50, 50, 50, 0.52);
	-moz-box-shadow:    0px 1px 6px 0px rgba(50, 50, 50, 0.52);
	box-shadow:         0px 1px 6px 0px rgba(50, 50, 50, 0.52);
    background: #000000;
    margin-left: auto;
    margin-right: auto;"> 
            <img style="text-align: center;
    margin-left: auto;
    margin-right: auto;
    padding-bottom: 10%;
    padding-top: 10%; width: 30%;" src="https://scontent.flad2-1.fna.fbcdn.net/v/t1.0-1/p480x480/32266315_1673291579451882_1888681687680811008_n.png?_nc_cat=0&oh=8653248508faab3703d7eadb2c63c51f&oe=5BF3AD3A">
			<img style="text-align: center;
    margin-left: auto;
    margin-right: auto;
    padding-bottom: 10%;
    padding-top: 10%;" src="<?php 
				global $qrcodetag;
				$checkout;
				if (isset($_COOKIE["checkout"])) {
					$checkout = $_COOKIE["checkout"];
					echo $qrcodetag->getQrCodeUrl($checkout, 250,'UTF-8','L',4,0); 
					
				} else {
				    WC()->cart->empty_cart();
					header('Location: '.site_url());
				}
			?>">
				<div>
					<p style="text-align: center; margin: 0; font-size: 12px; color: white;"><div class="optionHelpKamba1">
                                            Abra o aplicativo Kamba no seu telemóvel e digitalize o código de pagamento.
                                        </div></p>
					<p style="text-align: center; margin: 0; font-size: 12px; color: white; ">Não tem uma conta Kamba? <a style="color: #2CE080;" href="https://usekamba.com/"  target="_blank" class="appLinkKamba"> Faça download do aplicativo.</a></p>
				    <a href="<?php echo $_COOKIE["mobile_checkout"]; ?>" class="button btn-pay">Clica para pagar com Kamba <img class="img-pay-logo hide-for-medium-down" src="https://comerciante.usekamba.com/assets/PayLogo-dbaac0f05860ac5501544cabf023e4b768a079dbd7623e78c610dd161b9cef3c.png"></a>
				</div>
		</div>
			

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();

