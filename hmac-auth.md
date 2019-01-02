# Documentação HMAC para Checkouts
> Nota: Essa documentação se destina a desenvolvedores que desejam fazer checkouts na api da Kamba

Para fazer Checkouts na api da Kamba é necessário que a requisição POST relacionada ao checkout esteja assinada usando o método de autenticação **HMAC-SHA1**. A assinatura é necessária para assegurar a autenticidade e integridade da requisição. O HMAC é um algorítimo que  gerar um MAC (código de autenticação de mensagem) criptograficamente seguro a partir de uma **chave secreta** e de uma **mensagem**. Essa **chave secreta** é compartilhada pelos dois lado da comunicação. O emissor, criptografa a mensagem usando uma chave secreta, gerando assim uma assinatura e faz o envio a mensagem e da assinatura ao receptor. O receptor, usando a mesma chave secreta criptografa a mensagem recebida, gerando igualmente uma assinatura. Se a assinatura recebida for igual a assinatura gerada pelo receptor, então o conteúdo é autentico e íntegro.

O HMAC usa uma função de hash internamente. Essa função de hash pode ser qualquer hash criptográfico, como md5, sha1 ou sha256 e dependendo da função de hash que você usar você fica com uma versão diferente do HMAC (HMAC-MD5, HMAC-SHA1, HMAC-SHA256, etc). Para o checkout usamos o **HMAC-SHA1**.

## Fazendo a requisição
**Passo 1: Criar a canonical_string**
Para criar a assinatura é necessário montar a canonical_string ela tem a seguinte estrutura:
`canonical_string = "{http-method},{content-Type},{body-md5},{endpoint-uri},{time}"`

* http-method - representa o método http da requisição - Ex: `POST`
* content-Type - Ex: `application/json`
* body-md5 - representa a string md5 calculada a partir do json do body da requisição em forma de string. Ex: `/WaMa6Hp0P90XRLMKl2IAQ==`
* endpoint-uri - Ex: `/v1/checkouts`
* time - representa o tempo em que a requisição foi gerada, no formato RFC 2616, o mesmo * valor presente na key time - Ex: `Wed, 19 Dec 2018 11:48:48 GMT`

Exemplo da canonical_string montada: `"POST,application/json,/WaMa6Hp0P90XRLMKl2IAQ==,/v1/checkouts,Wed, 19 Dec 2018 11:48:48 GMT"`
**Passo 2: Criar a assinatura HMAC**
Lembrando que o HMAC usa uma **mensagem + chave secreta**, a canonical_string previamente calulada é a **mensagem** que será encriptada usando a **chave secreta**, gerando assim a assinatura HMAC. A chave secreta é uma propriedade do Merchant que estará criando o checkout. O procedimento específico para gerar a assinatura vai depender do cliente, mas o procedimento geral é o seguinte:
* Especificar SHA1 como a função hash a ser usanda pelo algoritmo HMAC
* Gerar a assinatura usando canonical_string e a CHAVE_SECRETA_DO_MERCHANT

**Assinatura-HMAC** = `HMAC(SHA1, canonial_string, CHAVE_SECRETA_DO_MERCHANT)`

A assinatura deve ser uma string hexadecimal. Posteriormente, a assinatura deve ser codificada usando Essa assinatura é então codifica usando base64-encode, o valor dessa codificação vai ser o valor do campo `signature` no header da requisição.

**Passo 3: Montando o header**

O header da requisição tem os seguintes campos e estrutura:
```
  -H 'authorization: Token SUA_CHAVE_DA_API' \
  -H 'content-type: application/json' \
  -H 'signature: fNrnAwHhSmEB+SkCQlGZUm4+VyQ=' \
  -H 'time: Wed, 19 Dec 2018 10:01:43 GMT' \
```

O campo `signature` foi previamente explicado no **passo 2**. O campo `time` representa o tempo em que a requisição foi gerada, lembrando que deve ser o mesmo valor usado para calcular a canonical_string no **passo 1**.


Post exemplo:
```
curl -X POST \
  https://API-URL/checkouts \
  -H 'authorization: Token SUA_CHAVE_DA_API' \
  -H 'content-type: application/json' \
  -H 'signature: fNrnAwHhSmEB+SkCQlGZUm4+VyQ=' \
  -H 'time: Wed, 19 Dec 2018 10:01:43 GMT' \
  -d '{
  "channel": "WEB",
  "initial_amount": 5500,
  "notes": "Alguma note exemplo.",
  "redirect_url_success": "http://amarildolucas.com/curso/aplicativo-movel-com-swift/sucesso"
}'
```

**Respostas**:

**Success 200**:
```
{
    "id": "0dfa1cb8-1490-4131-bc72-542e316e3722",
    "transaction_type": "CHECKOUT",
    "status": "WAITING",
    "redirect_url_success": "http://amarildolucas.com/curso/aplicativo-movel-com-swift/sucesso",
    "initial_amount": 5500,
    "fee": 0,
    "total_amount": 5500,
    "notes": "Alguma note exemplo.",
    "merchant": {
        "id": "79247905-737f-4772-9880-64adb02cc992",
        "business_name": "Restaurante Picasso",
        "phone_number": "929793316",
        "email": "airtodddncpu@hotmail.com"
    },
    "expires_at": "2018-12-21T10:02:32.904Z",
    "qr_code": "<?xml version=\"1.0\" stand..</svg>"
}
```
**Error 4xx**:
Se a `signature` represente no header não for válida:
```
{
    "errors": [
        {
            "message": "requisição não autenticada. A assinatura (signature) não é válida."
        }
    ]
}
```
As uma vez montadas, as requisições tem uma validade de 15 minutos, depois disso expiram:
```
{
    "errors": [
        {
            "message": "requisição expirada."
        }
    ]
}
```
Se existirem parâmetros em falta:
```
{
    "errors": [
        {
            "message": "parâmetros em falta no header da requisição."
        }
    ]
}
```
Se o `time` não estiver no formado correcto:
```
{
    "errors": [
        {
            "message": "o parâmetro time não está no formato RFC 2616."
        }
    ]
}
```
Não podem existir duas requisições com a mesma assinatura:
```
{
    "errors": [
        {
            "message": "assinatura repetida."
        }
    ]
}
```
Se a chave secreta do Merchant estiver expirada:
```
{
    "errors": [
        {
            "message": "chave secreta espirada."
        }
    ]
}
