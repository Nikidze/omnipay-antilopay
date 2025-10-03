<?php

namespace Omnipay\Antilopay\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getProjectId(): string
    {
        return $this->getParameter('projectId');
    }

    public function setProjectId(string $projectId): void
    {
        $this->setParameter('projectId', $projectId);
    }

    public function getSecretId(): string
    {
        return $this->getParameter('secretId');
    }

    public function setSecretId(string $secretId): void
    {
        $this->setParameter('secretId', $secretId);
    }

    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->setParameter('secretKey', $secretKey);
    }

    public function getCallbackKey(): string
    {
        return $this->getParameter('callbackKey');
    }

    public function setCallbackKey(string $callbackKey): void
    {
        $this->setParameter('callbackKey', $callbackKey);
    }

    public function getUrl(): string
    {
        return 'https://lk.antilopay.com/api/v1';
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Apay-Secret-Id' => $this->getSecretId(),
            'X-Apay-Sign-Version' => '1',
        ];
    }

    protected function signData(array $data): string
    {
        $rawSign = "";

        $jsonData = json_encode($data, JSON_THROW_ON_ERROR);
        openssl_sign($jsonData, $rawSign, $this->getSecretKey(), OPENSSL_ALGO_SHA256);

        return base64_encode($rawSign);
    }

    protected function verifyData(string $rawData, string $sign): bool
    {
        $rawSign = base64_decode($sign);

        return openssl_verify($rawData, $rawSign, $this->getCallbackKey(), OPENSSL_ALGO_SHA256) === 1;
    }
}
