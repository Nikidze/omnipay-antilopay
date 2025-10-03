<?php

namespace Omnipay\Antilopay\Model;

readonly class TransactionReference
{
    public function __construct(
        public string $orderId,
        public float $amount,
        public float $originalAmount,
        public float $fee,
        public string $currency,
        public string $productName,
        public string $description,
        public string $payMethod,
        public string $payData,
        public string $customerIp,
        public string $customerUserAgent,
        public array $customer,
    ) {}

    public function getCustomer(): CustomerReference
    {
        return new CustomerReference(
            $this->customer['email'] ?? '',
            $this->customer['phone'] ?? '',
            $this->customer['address'] ?? '',
            $this->customer['ip'] ?? '',
            $this->customer['fullname'] ?? ''
        );
    }
}
