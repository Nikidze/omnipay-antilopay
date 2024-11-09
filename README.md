# Omnipay: Antilopay
Antilopay online acquiring support for Omnipay

[![Total Downloads](https://img.shields.io/packagist/dt/receiver1/omnipay-antilopay.svg?style=flat-square)](https://packagist.org/packages/receiver1/omnipay-antilopay)
[![Latest Version](https://img.shields.io/packagist/v/receiver1/omnipay-antilopay.svg?style=flat-square)](https://github.com/receiver1/omnipay-antilopay/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Already Implemented
* Payment creation
* Payment notifications

## To Be Implemented
* Payment information
* Payment cancellation
* Withdraw creation
* Withdraw information
* Refund creation
* Refund information
* Project balance
* Error codes

## Installation
```bash
composer require league/omnipay receiver/omnipay-antilopay
```

## Usage
### Gateway Initialization
```php
// Create a new payment gateway
$gateway = Omnipay::create('Antilopay');

// Set the secret code
$gateway->setProjectId('PROJECT ID');
$gateway->setSecretId('SECRET ID');
// Antilopay uses RSA keys, so you need to make PEM inserts so that OpenSSL can distinguish it. It will be more efficient to load the key from a file.
$gateway->setSecretKey("-----BEGIN RSA PRIVATE KEY-----\nSECRET KEY\n-----END RSA PRIVATE KEY-----");
$gateway->setCallbackKey("-----BEGIN PUBLIC KEY-----\nCALLBACK KEY\n-----END PUBLIC KEY-----");
```

### Payment Creation
```php
// Create a new payment for 100 rubles 00 kopecks
$purchaseResponse = $gateway->purchase([
  'amount' => 100,
  'orderId' => '1',
  'product_name' => 'Balance top-up',
  'product_type' => 'services',
  'description' => 'Balance top-up 1337 Cheats',
  'customer.email' => 'yourCustomer@emailAddress.ru'
])->send();

if (!$purchaseResponse->isSuccessful()) {
  throw new Exception($response->getMessage());
}

// Get the payment identifier in Antilopay
$invoiceId = $purchaseResponse->getTransactionId();
// Get the link to the Antilopay payment form
$redirectUrl = $purchaseResponse->getRedirectUrl();
```

### Payment Verification
```php
$notification = $gateway->acceptNotification($data);
if (
  $notification->isValid() && 
  $notification->getTransactionStatus() 
    === NotificationInterface::STATUS_COMPLETED
) {
  /** @var TransactionReference $incomingTransaction */
  $incomingTransaction = $notification->getTransactionReference();
  print ($incomingTransaction->getAmount());
}
```