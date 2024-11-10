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
$purchaseRequest = $gateway->purchase([
  'amount' => 100,
  'orderId' => '1',
  'product_name' => 'Balance top-up',
  'product_type' => 'goods',
  'product_quantity' => 100,
  'vat' => 10,
  'description' => 'Balance top-up 1337 Cheats',
  'returnUrl' => 'https://leet-cheats.ru/payment/success',  // success_url
  'cancelUrl' => 'https://leet-cheats.ru/payment/fail',     // fail_url
  'customer' => new CustomerReference(
    email: 'customer-email@example.com', 
    phone: '1234567890', 
    address: '123 Customer Street', 
    ip: '192.168.0.1', 
    fullname: 'Customerov Customer Customerovich'
  ),
  'prefer_methods' => ['SBP', 'SBER_PAY', 'CARD_RU'],
]);
// alternative way to set data
$purchaseRequest->setAmount(100);
$purchaseRequest->setOrderId('1');
$purchaseRequest->setProductName('Balance top-up');
$purchaseRequest->setProductType('goods');
$purchaseRequest->setProductQuantity(100);
$purchaseRequest->setVat(10);
$purchaseRequest->setDescription('Balance top-up 1337 Cheats');
$purchaseRequest->setReturnUrl('https://leet-cheats.ru/payment/success');
$purchaseRequest->setCancelUrl('https://leet-cheats.ru/payment/fail');
$purchaseRequest->setCustomer(new CustomerReference(
  email: 'customer-email@example.com', 
  phone: '1234567890', 
  address: '123 Customer Street', 
  ip: '192.168.0.1', 
  fullname: 'Customerov Customer Customerovich'
));
$purchaseRequest->setPreferMethods(['SBP', 'SBER_PAY', 'CARD_RU']);

$purchaseResponse = $purchaseRequest->send();
if (!$purchaseResponse->isSuccessful()) {
  throw new Error($response->getMessage(), $response->getCode());
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
  /** @var TransactionReference $transaction */
  $transaction = $notification->getTransactionReference();
  var_dump([
    $transaction->getOrderId(),
    $transaction->getAmount(),
    $transaction->getOriginalAmount(),
    $transaction->getFee(),
    $transaction->getCurrency(),
    $transaction->getProductName(),
    $transaction->getDescription(),
    $transaction->getPayMethod(),
    $transaction->getPayData(),
    $transaction->getCustomerIp(),
    $transaction->getCustomerUserAgent(),
  ]);

  $customer = $transaction->getCustomer();
  var_dump([
    $customer->getEmail(),
    $customer->getPhone(),
    $customer->getAddress(),
    $customer->getIp(),
    $customer->getFullname(),
  ]);
}
```