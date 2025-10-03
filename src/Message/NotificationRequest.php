<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Antilopay\Message\AbstractRequest;
use Omnipay\Antilopay\Model\TransactionReference;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Message\NotificationInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class NotificationRequest extends AbstractRequest implements NotificationInterface
{
    private array $data;

    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);

        $this->data = json_decode($httpRequest->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function sendData($data): self
    {
        return $this;
    }

    public function isValid(): bool
    {
        $content = $this->httpRequest->getContent();
        $signature = $this->httpRequest->headers->get('X-Apay-Callback');

        return $this->verifyData($content, $signature);
    }

    public function getTransactionReference(): TransactionReference
    {
        return new TransactionReference(
            $this->data['order_id'] ?? '',
            (float) ($this->data['amount'] ?? 0),
            (float) ($this->data['original_amount'] ?? 0),
            (float) ($this->data['fee'] ?? 0),
            $this->data['currency'] ?? '',
            $this->data['product_name'] ?? '',
            $this->data['description'] ?? '',
            $this->data['pay_method'] ?? '',
            $this->data['pay_data'] ?? '',
            $this->data['customer_ip'] ?? '',
            $this->data['customer_useragent'] ?? '',
            $this->data['customer'] ?? []
        );
    }

    public function getTransactionStatus(): string
    {
        return ($this->data['status'] ?? '') === 'SUCCESS'
            ? NotificationInterface::STATUS_COMPLETED
            : NotificationInterface::STATUS_FAILED;
    }

    public function getMessage(): string
    {
        return '';
    }
}
