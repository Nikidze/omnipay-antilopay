<?php

namespace Omnipay\Antilopay\Model;

class TransactionReference {
  private $orderId;
  private $amount;
  private $originalAmount;
  private $fee;
  private $currency;
  private $productName;
  private $descripion;
  private $payMethod;

  public function __construct($orderId, $amount, $originalAmount, $fee, $currency, $productName, $description, $payMethod) {
    $this->orderId = $orderId;
    $this->amount = $amount;
    $this->originalAmount = $originalAmount;
    $this->fee = $fee;
    $this->currency = $currency;
    $this->productName = $productName;
    $this->description = $description;
    $this->payMethod = $payMethod;
  }

  public function getOrderId(): string {
    return $this->orderId;
  }

  public function getAmount(): int {
    return $this->amount;
  }

  public function getOriginalAmount(): int {
    return $this->originalAmount;
  }

  public function getFee(): int {
    return $this->fee;
  }

  public function getCurrency(): string {
    return $currency;
  }

  public function getProductName(): string {
    return $productName;
  }

  public function getDescription(): string {
    return $description;
  }

  public function getPayMethod(): string {
    return $payMethod;
  }

  // TODO: pay_data, customer_ip, customer_useragent, customer.email, customer.phone, customer.address, customer.ip, customer.fullname
}