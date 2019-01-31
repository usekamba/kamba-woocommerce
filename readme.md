# Integração Kamba Woocommerce

> **Nota:** Esta biblioteca está em **fase beta** e em desenvolvimento contínuo, mas completamente funcional em lojas online. Nos envie feedback ao abrir uma issue diretamente aqui mesmo no Github.

**Ofereça pagamentos de produtos ou serviços em seu website.**

Com uma única **integração multicanal**, seus clientes poderão realizar pagamentos com a carteira móvel via código QR de pagamento ou Botão de pagamento, além de utilizarem seus dados cadastrados para futuras compras. Notificação para lembretes de finalização de compra, levantamento da quantia para sua conta bancária e vários benefícios técnicos e de negócios à longo termo.

## Formas de pagamento

**Pagamento via QR:** Os usuários adicionam produtos para o carrino de compras da loja, e no checkout escolhem **pagar com Kamba** como meio de pagamento. Ao clicar em Finalizar Compra será gerado um código qr que poderá ser escaneado com a carteira móvel.

> **Nota:** O pagamento QR também funciona In-Store (dentro da loja física) do comerciante.

**Pagamento Web2App** Caso os usuários estejam a navegar no smarphone eles não poderão escanear o código QR. Para efectuar o pagamento neste caso, o usuário poderá clicar no botão Pagar com Kamba para finalizar o pagamento com a carteira instalada em seu dispositivo móvel.

> **Nota:** Você acompanha os estados do pagamento através do [painel comerciante](https://comerciante.usekamba.com/entrar), recebe notificações por e-mail e push no seu telemóvel quando pagamentos são bem sucedidos.

## Dependências

### QR Code Tag - Responsável por gerar o código QR que será escaneado pelo seu cliente.
#### Como instalar  

1. Envie o ficheiro ```qr-code-tag``` por meio de FTP para a pasta ```/wp-content/plugins/``` no seu server ou 
faça upload do plugin através do menu plugins. 
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.

### Theme Customisations
#### Como instalar  

1. Envie o ficheiro ```theme-customisations.zip``` por meio de FTP para a pasta ```/wp-content/plugins/``` no seu server ou 
faça upload do plugin através do menu plugins. 
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.

## Instalação do plugin Kamba Woocommerce
> **Nota:** Para usar o plugin Kamba Woocommerce certifica que as dependências acima já foram instaladas
1. Envie a pasta ```kamba-woocommerce-plugin``` para ```/wp-content/plugins/``` no seu server.
2. Activa o plugin através do menu ```plugins``` em seu site wordpress.
4. Nas configurações do plugin Kamba Woocommerce preenche os dados necessários para configuração do checkout: 
    > 1) ID de comerciante
    > 2) Chave da API
    > 3) Chave secreta da API
5. Salva as configurações 
6. Envie o ficheiro ```theme/kamba-checkout-qr.php``` para a pasta themes/seu-tema/templates/ que contém o tema actual do seu site wordpress. Esta página irá apresentar o checkout Kamba para que seus usuários possam visualizar o checkout e escanear o código QR usando a carteira Kamba disponível na play store e app store. 
7. Crie uma pagina nova com o seguinte título ```kamba-checkout-qr```. Certifica que essa página usa o seguinte permalink: http://seu-site.com/kamba-checkout/ 
e na secção Atributos da página define o Kamba Checkout QR como template para esta página. 

NOTA: A localização da pasta ```templates``` muda consoante o seu tema, terá que certifica que é a pasta correcta antes de copiar o ficheiro ```theme/kamba-checkout-qr.php```.


## Histórico de versões
``` 0.0.1: Versão Inicial - 30/08/2018 ``` <br/>
``` 0.0.2: Foi alterado os links de pagamanetos - 11/12/2018 ``` <br/>
``` 0.0.3: Implementamos autenticação hmac e actualizamos os links de pagamento - 31/01/2019 ``` <br/>

© 2018 Soluções de Pagamento. Todos os direitos reservados. USEKAMBA, LDA. - Rua Avenida Manuel Vandunem, Ingombotas - Luanda - Angola