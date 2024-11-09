<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Antilopay\Message\AbstractRequest;
use Omnipay\Antilopay\Model\TransactionReference;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Message\NotificationInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class NotificationRequest extends AbstractRequest implements NotificationInterface {
  private $data;

  public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest) {
    parent::__construct($httpClient, $httpRequest);

    $this->data = json_decode($httpRequest->getContent(), true);
  }

  public function getData(): array {
    return $this->data;
  }

  public function sendData($data) {
    return $this;
  }

  public function isValid(): bool {
    return $this->verifyData($this->httpRequest->getContent(), $this->httpRequest->headers->get('X-Apay-Callback'));
  }

  public function getTransactionReference(): TransactionReference {
    return new TransactionReference($this->data['order_id'], $this->data['amount'],
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