## Kamba WooCommerce Plugin 

Ofereça pagamentos de produtos ou serviços em seu website que usa wordpress.

Com uma única integração, seus clientes poderão realizar pagamentos com a sua carteira via código QR de pagamento.

> A autenticação deve ser feita com as suas credenciais de conta Comerciante. 
Para criar a sua conta comerciante para production pode acessar este link: https://comerciante.usekamba.com


## Formas atuais de pagamento
1. **Pagamento via QR** Os usuários adicionam produtos para seu carrino, e no checkout escolhem pagar com Kamba como meio de pagamento. Ao clicar "place order" será gerado um código qr que poderá ser escaneado com a carteira Kamba. 

2. **Pagamento Web2App** Caso os usuários estejam a navegar no smarphone eles não poderão escanear o código QR. Para efectuar o pagamento neste caso o usuário poderá clicar no botão "Pagar com Kamba" para terminar o pagamento com a carteira Kamba.

> **Nota:** Em ambos você acompanha os estados do pagamento, recebe notificações por e-mail, push quando pagamentos são bem sucedidos. E pode ainda controlar todos seus pagamentos com o aplicativo para Comerciantes ou o Painel Web.

## Configuração inicial
Crie uma conta Comerciante connosco entrando em contato com nossa equipe de suporte. Você receberá uma `api_key` e um identificador de comerciante `mID`  para testar a biblioteca no modo SANDBOX. Você terá ainda acesso ao App Comerciante e ao Painel Web para adiministração e controle dos seus pagamentos e clientes.

> Nota: Este plugin está em fase beta e em desenvolvimento contínuo. Se você encontrar algum erro, crie uma issue para que ela seja corrigida o mais rápido possível.

## Dependências

### QR Code Tag - Responsável por gerar o código QR que será escaneado pelo seu cliente.
#### Como instalar  
1. Envie a pasta ```qr-code-tag``` para ```/wp-content/plugins/``` no seu server.
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.

### Theme Customisations
#### Como instalar  
1. Envie a pasta ```theme-customsations``` para ```/wp-content/plugins/``` no seu server.
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.

## Instalação do plugin Kamba Woocommerce
> **Nota:** Para usar o plugin Kamba Woocommerce certifica que as dependências acima já foram instaladas
1. Envie a pasta ```kamba-woocommerce-plugin``` para ```/wp-content/plugins/``` no seu server.
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.
3. Envie o ficheiro ```theme/kamba-checkout-qr.php``` para a pasta themes/seu-tema/templates/ que contém o tema actual do seu site wordpress.


## Histórico de versões
0.0.1: Initial version - 30/08/2018
0.0.2: Altered payment link implementation to dynamic links instead of deep links - 30/08/2018

© 2018 Soluções de Pagamento. Todos os direitos reservados. USEKAMBA, LDA. - Rua Avenida Manuel Vandunem, Ingombotas - Luanda - Angola