<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Antilopay\Message\AbstractRequest;
use Omnipay\Antilopay\Model\TransactionReference;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Message\NotificationInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class NotificationRequest extends AbstractRequest implements NotificationInterface {
  private $isValid;
  private $data;

  public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest) {
    parent::__construct($httpClient, $httpRequest);

    $this->isValid = $this->verifyData($httpRequest->getContent(), $this->getHeaders()['X-Apay-Callback']);
    $this->data = json_decode($httpRequest->getContent(), true);
  }

  public function getData(): array {
    return $this->data;
  }

  public function sendData($data) {
    return $this;
  }

  public function isValid(): bool {
    return $this->isValid;
  }

  public function getTransactionReference(): TransactionReference {
    return new TransactionReference($this->data['orderId'], $this->data['amount'],
      $this->data['amount'], $this->data['fee'], $this->data['currency'],
      $this->data['product_name'], $this->data['description'], $this->data['pay_method']);
  }

  public function getTransactionStatus(): string {
    if ($this->data['status'] == 'SUCCESS') {
      return NotificationInterface::STATUS_COMPLETED;
    }
    return NotificationInterface::STATUS_FAILED;
  }

  public function getMessage() {
    return null;
  }
}