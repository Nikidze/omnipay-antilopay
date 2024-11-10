<?php

namespace Omnipay\Antilopay\Message;

use Omnipay\Antilopay\Model\CustomerReference;

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

  public function getProductQuantity() {
    return $this->getParameter('product_quantity');
  }

  public function setProductQuantity(int $productQuantity): void {
    $this->setParameter('product_quantity', $productQuantity);
  }

  public function getVat() {
    return $this->getParameter('vat');
  }

  public function setVat(int $vat): void {
    $this->setParameter('vat', $vat);
  }

  public function getPreferMethods() {
    return $this->getParameter('prefer_methods');
  }

  public function setPreferMethods(array $preferMethods): void {
    $this->setParameter('prefer_methods', $preferMethods);
  }

  public function getCustomer(): CustomerReference {
    return $this->getParameter('customer');
  }

  public function setCustomer(CustomerReference $customerReference): void {
    $this->setParameter('customer', $customerReference);
  }

  public function getData(): array {
    $this->validate('amount', 'order_id', 'product_name', 'product_type', 'description', 'customer');

    $customerData = [];

    if ($this->getCustomer()->getEmail())
      $customerData['email'] = $this->getCustomer()->getEmail();
    if ($this->getCustomer()->getPhone())
      $customerData['phone'] = $this->getCustomer()->getPhone();
    if ($this->getCustomer()->getAddress())
      $customerData['address'] = $this->getCustomer()->getAddress();
    if ($this->getCustomer()->getIp())
      $customerData['ip'] = $this->getCustomer()->getIp();
    if ($this->getCustomer()->getFullname())
      $customerData['fullname'] = $this->getCustomer()->getFullname();

    $data = [
      'project_identificator' => $this->getProjectId(),
      'amount' => intval($this->getAmount()),
      'order_id' => $this->getOrderId(),
      'product_name' => $this->getProductName(),
      'product_type' => $this->getProductType(),
      'currency' => 'RUB',
      'description' => $this->getDescription(),
      'customer' => $customerData,
    ];

    if ($this->getProductQuantity())
      $data['product_quantity'] = $this->getProductQuantity();
    if ($this->getVat())
      $data['vat'] = $this->getVat();
    if ($this->getReturnUrl())
      $data['success_url'] = $this->getReturnUrl();
    if ($this->getCancelUrl())
      $data['fail_url'] = $this->getCancelUrl();
    if ($this->getPreferMethods())
      $data['prefer_methods'] = $this->getPreferMethods();

    return $data;
  }

  public function sendData($requestData) {
    $uri = "{$this->getUrl()}/payment/create";

    $headers = $this->getHeaders();
    $headers['X-Apay-Sign'] = $this->signData($requestData);
    $response = $this->httpClient->request('POST',
      $uri, $headers, json_encode($requestData));

    $responseData = json_decode($response->getBody(), true);
    $this->response = new PurchaseResponse($this, $responseData);
    return $this->response;
  }
}