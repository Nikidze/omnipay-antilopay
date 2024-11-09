# Omnipay: Antilopay
Antilopay online acquiring support for Omnipay

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
composer require league/omnipay receiver1/omnipay-antilopay
```

## Usage
### Gateway Initialization
```php
// Create a new payment gateway
$gateway = Omnipay::create('Antilopay');

// Set the secret code
$gateway->setProjectId('yourProjectIdThere');
$gateway->setSecretId('yourSecretIdThere');
$gateway->setSecretKey("-----BEGIN RSA PRIVATE KEY-----\nyourSecretKeyThere\n-----END RSA PRIVATE KEY-----");
```

### Payment Creation
```php
// Create a new payment for 100 rubles 00 kopecks
$purchaseResponse = $gateway->purchase([
  'amount' => 100,
  'orderId' => '1',
  'currency' => 'RUB',
  'productName' => 'Balance top-up',
  'productType' => 'services',
  'description' => 'Balance top-up 1337 Cheats',
  'email' => 'yourCustomer@emailAddress.ru'
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