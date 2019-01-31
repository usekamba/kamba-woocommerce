
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<style type="text/css">

@import url('https://fonts.googleapis.com/css?family=Montserrat');

body {
    font-family: 'Montserrat', sans-serif;
    color: #848484;
    font-size: 1.0rem;
}

.center {
    margin-left: auto;
    margin-right: auto;
}

.div {
    padding: 16px;
    padding-top: 0px;
    text-align: center;
}

li {
    list-style: none;
}

.checkout-card > p {
    color: #848484;
    text-align: center;
}

.checkout-card {
    min-width: 320px;
    max-width: 500px;
    min-height: 552px;
    background: #FFFFFF;
    border: 1px solid #FFFFFF;
    box-shadow: 0 2px 3px 0 rgba(176,176,176,0.50);
    border-radius: 8px;
    padding: 0%;
    font-family: 'Montserrat', sans-serif;
}

.card-toolbar {
    max-height: 40px;
    padding: 2%;
    border-top-right-radius: 8px;
    border-top-left-radius: 8px;
    background-image: linear-gradient(-180deg, #89ff5e 0%, #01ff5f 100%);
}

.card-container {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-between;
}

.card-toolbar > ul {
    list-style: none;
}

.card-toolbar > ul > li {
    color: white;
    float: left;
    margin-right: 18%;
}

.card-toolbar > ul > li:nth-child(1) {
    margin-left: -10px;
}

.title {
    font-weight: bold;
    cursor: none;
}

.title:hover {
    cursor: default;
}

.card-toolbar > ul > li > a {
    text-decoration: none;
    color: white;
}



.card-business-info > div {
    height: 70px;
    padding: 16px;
}

 .card-business-info > div > img {
    width: 64px;
    float: left;
    margin-right: 16px;
    margin-top: 0px;
 }

 .card-business-info > div > ul {
     
 }

 .business-name {
     color: #848484;
     margin-bottom: 4px;
 }

 .business-info { 
    text-align: start;
 }

 .checkout-amount {
     color: black;
     font-weight: bolder;
 }

hr {
    background-color: #EFEFEF;
    height: 1px;
    border: none;
}

.checkout-qr-code {
    border: 1px solid black;
    width: 200px;
    margin-left: auto;
    margin-right: auto;
    display: block;
}

.how-to {
    margin-left: 16px;
    margin-right: 16px;
}

.logo {
    width: 32px;
    height: 32px;
}

.icon {
    margin-right: 8px;
}

.btnOpenWidgetKamba {
    background-image: linear-gradient(to left, rgb(0, 255, 179), rgb(0, 255, 95));
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 0.3rem;
    font-family: Montserrat, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
    
}

.logo {
    width: 33px;
    display: inline;
    float: left;
}

.course-curriculum .course-button {
    font-weight: 900;
    background-color: #2687fb;
    border-radius: 4px;
    width: 100%;
}

.button.large {
    font-size: 1.0rem;
}

.btnOpenWidgetKamba {
    margin: 0 auto;
    display: block;
    text-align: center;
}

.button {
    display: inline-block;
    vertical-align: middle;
    margin: 0 0 1rem 0;
    font-family: inherit;
    padding: 0.85em 1em;
    -webkit-appearance: none;
    border: 1px solid transparent;
    border-radius: 0;
    -webkit-transition: background-color 0.25s ease-out, color 0.25s ease-out;
    transition: background-color 0.25s ease-out, color 0.25s ease-out;
    font-size: 0.8rem;
    line-height: 1;
    text-align: center;
    cursor: pointer;
    color: #000000;
    text-decoration: none;
    border-radius: 4px;
}

.img-pay-logo {
    width: 52px;
    height: 28px;
}

button, input, optgroup, select, textarea {
    font-family: inherit;
}

button {
    padding: 0;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border: 0;
    border-radius: 0;
    background: transparent;
    line-height: 1;
    cursor: auto;
}

#desktopshow {
    display: none;
}

#mobileshow { 
    display: none; 
}

@media (min-width: 500px) {     
    #desktopshow { 
        display: block; 
    }
}
    
@media screen and (max-width: 500px) {
    #mobileshow { 
        display: block; 
    }

    .card-toolbar > ul > li {
        margin-right: 16%;
    }
    
    * {
        font-size: 0.8rem;
    }
    
    .img-pay-logo {
        width: 32px;
        height: 18px;
    }
    
    .checkout-card {
        max-width: 100% !important;
        max-height: 100% !important;
    }
}

</style>

<?php
/**
 *
 * Template Name: Kamba Checkout QR
 */
 
function formatKwanzas($money)
{
    return "Kz ".number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $money)), 2);
}
  ?>
  <head>
       <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
<body>  
<div class='center checkout-card'>
            <div class='div card-toolbar'>
                <ul class='card-container'>
                    <li>
                        <a href="">Cancelar</a></li>
                    <li><a class='title' href='#'>Nova transação</a></li>
                    <li><a href=''></a></li>
                </ul>
            </div>
            <div class='div card-business-info'>
                <div class='div'>
                    <img class='' src='https://raw.githubusercontent.com/usekamba/kamba-checkout-universal-design/master/assets/img/merchant-icon.png'/>
                    <ul>
                        <li class='business-info business-name'>
                            <?php echo $_COOKIE["business_name"]; ?>
                        </li>
                        <li class='business-info checkout-amount'>
                            <?php echo (formatKwanzas($_COOKIE["total_amount"] )); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class='div'>
                <p><?php echo $_COOKIE["notes"]; ?></p>
                <img id='desktopshow' class ='checkout-qr-code' src="<?php 
                    global $qrcodetag;
                    $checkout;
                    if (isset($_COOKIE["checkout"])) {
                        $checkout = $_COOKIE["checkout"];
                        echo $qrcodetag->getQrCodeUrl($checkout, 290,'UTF-8','L',1, 0); 
                        
                    } else {
                        WC()->cart->empty_cart();
                        header('Location: '.site_url());
                    }
			    ?>">
                <p><?php echo $_COOKIE["expiration_date"]; ?></p>
                <p id='desktopshow' class='how-to checkout-amount'>
                    Abra a sua Carteira Kamba e escaneia o código de pagamento
                </p>
                <a id="mobileshow" href="<?php echo $_COOKIE["mobile_checkout"]; ?>" class="btnOpenWidgetKamba button large course-button">Clica para pagar com Kamba 
                    <img class="img-pay-logo logo hide-for-medium-down" src="https://github.com/usekamba/kamba-checkout-universal-design/raw/master/assets/img/PayLogo-kamba.png"></a>
                <div class='div'>
                    <img class='logo' src='https://github.com/usekamba/kamba-checkout-universal-design/raw/master/assets/img/kamba-logo.png' />
                    <p class='checkout-amount'><i class='icon fas fa-lock checkout-amount'></i>Conexão segura</p>
                </div>
            </div>
    </div>
</body>
<?php
