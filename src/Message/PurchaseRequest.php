<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Antilopay\Model\CustomerReference;

class PurchaseRequest extends AbstractRequest
{
    public function getOrderId(): string
    {
        return $this->getParameter('order_id');
    }

    public function setOrderId(string $orderId): void
    {
        $this->setParameter('order_id', $orderId);
    }

    public function getProductName(): string
    {
        return $this->getParameter('product_name');
    }

    public function setProductName(string $productName): void
    {
        $this->setParameter('product_name', $productName);
    }

    public function getProductType(): string
    {
        return $this->getParameter('product_type');
    }

    public function setProductType(string $productType): void
    {
        $this->setParameter('product_type', $productType);
    }

    public function getProductQuantity(): ?int
    {
        return $this->getParameter('product_quantity');
    }

    public function setProductQuantity(int $productQuantity): void
    {
        $this->setParameter('product_quantity', $productQuantity);
    }

    public function getVat(): ?int
    {
        return $this->getParameter('vat');
    }

    public function setVat(int $vat): void
    {
        $this->setParameter('vat', $vat);
    }

    public function getPreferMethods(): ?array
    {
        return $this->getParameter('prefer_methods');
    }

    public function setPreferMethods(array $preferMethods): void
    {
        $this->setParameter('prefer_methods', $preferMethods);
    }

    public function getCustomer(): CustomerReference
    {
        return $this->getParameter('customer');
    }

    public function setCustomer(CustomerReference $customerReference): void
    {
        $this->setParameter('customer', $customerReference);
    }

    public function getData(): array
    {
        $this->validate('amount', 'order_id', 'product_name', 'product_type', 'description', 'customer');

        $customer = $this->getCustomer();
        $customerData = [
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'ip' => $customer->ip,
            'fullname' => $customer->fullname,
        ];
        $customerData = array_filter($customerData, fn($value) => $value !== null && $value !== '');

        $data = [
            'project_identificator' => $this->getProjectId(),
            'amount' => (int) $this->getAmount(),
            'order_id' => $this->getOrderId(),
            'product_name' => $this->getProductName(),
            'product_type' => $this->getProductType(),
            'currency' => 'RUB',
            'description' => $this->getDescription(),
            'customer' => $customerData,
        ];

        if ($productQuantity = $this->getProductQuantity()) {
            $data['product_quantity'] = $productQuantity;
        }

        if ($vat = $this->getVat()) {
            $data['vat'] = $vat;
        }

        if ($returnUrl = $this->getReturnUrl()) {
            $data['success_url'] = $returnUrl;
        }

        if ($cancelUrl = $this->getCancelUrl()) {
            $data['fail_url'] = $cancelUrl;
        }

        if ($preferMethods = $this->getPreferMethods()) {
            $data['prefer_methods'] = $preferMethods;
        }

        return $data;
    }

    public function sendData($requestData): PurchaseResponse
    {
        $uri = "{$this->getUrl()}/payment/create";
        $headers = $this->getHeaders();
        $headers['X-Apay-Sign'] = $this->signData($requestData);

        $response = $this->httpClient->request('POST', $uri, $headers, json_encode($requestData));
        $responseData = json_decode($response->getBody()->getContents(), true);

        $this->response = new PurchaseResponse($this, $responseData);
        return $this->response;
    }
}
