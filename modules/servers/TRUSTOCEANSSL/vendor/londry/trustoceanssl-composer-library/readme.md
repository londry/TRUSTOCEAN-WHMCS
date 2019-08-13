Composer Library for TrustOgitcean SSL Reseller API in PHP

Register and Submit one ticket to get Free API Access account: https://www.trustocean.com
Feedback and Help: 
Please join our developer QQ Group(ID 941598653), or use submit issue on GitHub.

## Table of Contents

1. [Installation](#installation)
2. [Get Instance of SslOrder API](#Get_Instance_of_SslOrder_API)
2. [Create New SSL Order](#Create_New_SSL_Order)
4. [Check And Get Issued Certificate](#Check_And_Get_Issued_Certificate)

&nbsp;

## Installation
Use your composer:
```php
composer require londry/trustoceanssl-composer-library 
```
## Get_Instance_of_SslOrder_API
Before you try access api, you need add your local/server public ip address to the whitelist in your TrustOcean API Account.
```php
$newSslOrder = new SslOrder('api@example.com','ApiToken-replace-this-to-your-own');
```

## Create_New_SSL_Order
Example for apply one TrustOcean Encryption365 SSL certificate. Every time, you can also use this same logic to renew your current ssl certificate. "RENEW SSL" means you need verify your domains again and get new SSL certificate and must install it on your web server.
```php
$newSslOrder->setCertificateType(CertificateType::TrustOceanEncryption365Ssl);
$newSslOrder->setCertificatePeriod(CertificatePeriod::ThreeMonths);
$newSslOrder->setUniqueId('someUniqueStringHere');
$newSslOrder->setDomains(['example1.com','example2.com','example3.com','www.example3.com']);
$newSslOrder->setCsrCode(new Csr($request->get('-----BEGIN CERTIFICATE REQUEST----- MIIDADCCAegCAQAwgboxCzAJBgNVBAYTAkNOMRAwDgYDVQQIDAdTaGFhbnhpMQ0w CwYDVQQHDAR4aWFuMScwJQYDVQQKDB5UcnVzdE9jZWFuIENvcnBvcmF0aW9uIExp bWl0ZWQxJzAlBgNVBAsMHlRydXN0T2NlYW4gQ29ycG9yYXRpb24gTGltaXRlZDEV MBMGA1UEAwwMZXhhbXBsZTEuY29tMSEwHwYJKoZIhvcNAQkBFhJkZXYtYXBpQHFp YW9rci5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDgqiV7NBxX 0J+DEqiez/mo+ZIEYZjbY3HbD1BqoV6tl85K0Me9vwodSVl1jCNzI8aH/QkFRhai CCcdkbTmuIG9rlXP9YP9MDMI8LS1z18WPy+FLNojxOjwBM6HV9tLHqAxWm9GLi9c 9JBHKNwlehLu9Zp9TjnSyrR0fBKqinS0kWRk3kYjl6Afj4qhfGV5lWtqapffoLr/ sdbp+pAhGEKw/9hU5OlX46+DORLr19qznoaez3KswejfNhlCIp6Cm5YiVoQEPvlu rioe9BJXjQC5MF8brt7IuM0PClerUDwwZ3EAz2xR8JJvQVppu2CRdtKoVPpCr2hf zC6GEBiTb+LPAgMBAAGgADANBgkqhkiG9w0BAQUFAAOCAQEAIClbOKNVB/f4Gqm9 xZ5ky/PBoGps5yfp8Ezw8IitjiX7SJFtNCXrXK7g1X6pfs6EMf2RyL1PPlJbO4+9 dEiG0faitbJ5+314WMBDIylmhSK2AILncqZvefQjrSmRNEr7Dy4JlpTM9qawJsYq /Qx1kGgss6M6CoYzg75eHueOKRv88nGzmr6/m7lIHxK5Ihrr5AtGj83OyFvucfB6 DPv+1XZP7+EpiehoyWzoA3UzLBpfSppVtnYo4oBBujF8DrBBPsaauPt59uBN1B3h 2GB+Ce3NHpAFI/x730dTj1Cdpy/xV8Ew9yBQki2Ojhzw5Ehl6yoXOnFMj0Ja0Lme nMVDKQ== -----END CERTIFICATE REQUEST-----')));
$newSslOrder->setDcvMethod(['dns','http','https','admin@example3.com']);
$newSslOrder->setContactEmail('yourName@example.com');
# Call CA
$newSsl = $newSslOrder->callInit(NULL)->callCreate();
# Get Domain Validation Information and Order ID
$dcvInfo = $newSsl->getDcvInfo();
$orderId = $newSsl->getOrderId();
```

## Check_And_Get_Issued_Certificate
Reload ssl order from TrustOcean, you can easy to manage or get new certificate status.
```php
$sslOrder = $newSslOrder->callInit($orderId);
$sslOrderStatus = $sslOrder->getOrderStatus();
if($sslOrderStatus === "issued_active"){
    $certificate_content = $sslOrder->getCertCode(); # will be PEM content
    $ca_certificate_content = $sslOrder->getCaCode(); # will be PEM content
}
```

## Available_Methods_In_This_Lirary
This library can be used to develop PHP application/scripts that automatically apply ssl and 
automatically renew certificates on your web server. For non-automatic application/scripts use, you may 
also need the following API methods to more easily manage the order validation process 
and the life cycle of your ssl certificates.
```php
callReissue();
callChangeDcvMethod($domainName, $newMethod);
callRemoveDomainName($unverifiedDomainName);
callRetryDcvProcess();
callResendDcvEmails();
callGetDcvDetails();
callRevokeCertificate($revocationReason);
callCancelAndRevokeCertificate();
```