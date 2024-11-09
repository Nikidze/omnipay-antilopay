<?php

namespace Omnipay\Antilopay;

use Omnipay\Antilopay\Message\NotificationRequest;
use Omnipay\Antilopay\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;

class Gateway extends AbstractGateway {
  public function getName() {
    return 'Antilopay';
  }

  public function getProjectId(): string {
    return $this->getParameter('projectId');
  }

  public function setProjectId(string $projectId): void {
    $this->setParameter('projectId', $projectId);
  }

  public function getSecretId(): string {
    return $this->getParameter('secretId');
  }

  public function setSecretId(string $secretId): void {
    $this->setParameter('secretId', $secretId);
  }

  public function getSecretKey(): string {
    return $this->getParameter('secretKey');
  }

  public function setSecretKey(string $secretKey): void {
    $this->setParameter('secretKey', $secretKey);
  }

  public function getCallbackKey(): string {
    return $this->getParameter('callbackKey');
  }

  public function setCallbackKey(string $callbackKey): void {
    $this->setParameter('callbackKey', $callbackKey);
  }

  public function purchase(array $options) {
    return $this->createRequest(PurchaseRequest::class, $options);
  }

  public function acceptNotification(array $options = []): NotificationInterface {
    /** @var NotificationInterface $notification */
    $notification = $this->createRequest(NotificationRequest::class, $options);
    return $notification;
  }
}