<?php

namespace Omnipay\Antilopay\Message;

class PurchaseRequest extends AbstractRequest {
  public function getOrderId(): string {
    return $this->getParameter('order_id');
  }

  public function setOrderId(string $orderId): void {
    $this->setParameter('order_id', $orderId);
  }

  public function getProductName(): string {
    return $this->getParameter('product_name');
  }

  public function setProductName(string $productName): void {
    $this->setParameter('product_name', $productName);
  }

  public function getProductType(): string {
    return $this->getParameter('product_type');
  }

  public function setProductType(string $productType): void {
    $this->setParameter('product_type', $productType);
  }

  public function getEmail(): string {
    return $this->getParameter('customer.email');
  }

  public function setEmail(string $email): void {
    $this->setParameter('customer.email', $email);
  }

  public function getData(): array {
    $this->validate('amount', 'order_id', 'product_name', 'product_type', 'description', 'customer.email');

    $data = [
      'project_identificator' => $this->getProjectId(),
      'amount' => intval($this->getAmount()),
      'order_id' => $this->getOrderId(),
      'product_name' => $this->getProductName(),
      'product_type' => $this->getProductType(),
      'currency' => 'RUB',
      'description' => $this->getDescription(),
      'customer' => [
        'email' => $this->getEmail(),
      ]
    ];

    if ($this->getReturnUrl()) {
      $data['success_url'] = $this->getReturnUrl();
    }

    if ($this->getCancelUrl()) {
      $data['fail_url'] = $this->getCancelUrl();
    }

    return $data;
  }

  public function sendData($requestData) {
    $uri = "{$this->getUrl()}/payment/create";

    $headers = [...$this->getHeaders(), 'X-Apay-Sign' => $this->signData($requestData)];
    $response = $this->httpClient->request('POST',
      $uri, $headers, json_encode($requestData));

    $responseData = json_decode($response->getBody(), true);
    $this->response = new PurchaseResponse($this, $responseData);
    return $this->response;
  }
}