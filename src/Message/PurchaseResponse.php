<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface {
  public function isSuccessful(): bool {
    return $this->getData()['code'] === 0;
  }

  public function isRedirect(): bool {
    return true;
  }

  public function getCode(): int {
    return $this->getData()['code'];
  }

  public function getMessage(): string {
    return $this->getData()['error'];
  }

  public function getTransactionId(): string {
    return $this->getData()['payment_id'];
  }

  public function getRedirectUrl(): string {
    return $this->getData()['payment_url'];
  }

  public function getRedirectMethod(): string {
    return 'GET';
  }
}