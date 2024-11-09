<?php

namespace Omnipay\Antilopay\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest {
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

  public function getUrl(): string {
    return 'https://lk.antilopay.com/api/v1';
  }

  public function getHeaders(): array {
    return [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'X-Apay-Secret-Id' => $this->getSecretId(),
      'X-Apay-Sign-Version' => 1,
    ];
  }

  public function signData(array $data, bool $isCallback = false): string {
    $rawSign = "";
    openssl_sign(json_encode($data), $rawSign,
      !$isCallback ? $this->getSecretKey() : $this->getCallbackKey(), OPENSSL_ALGO_SHA256);
    return base64_encode($rawSign);
  }
}