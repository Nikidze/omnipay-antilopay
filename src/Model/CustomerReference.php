<?php

namespace Omnipay\Antilopay\Model;

class CustomerReference {
  private $email;
  private $phone;
  private $address;
  private $ip;
  private $fullname;

  public function __construct($email, $phone = null, $address = null, $ip = null, $fullname = null) {
    $this->email = $email;
    $this->phone = $phone;
    $this->address = $address;
    $this->ip = $ip;
    $this->fullname = $fullname;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getPhone() {
    return $this->phone;
  }

  public function getAddress() {
    return $this->address;
  }

  public function getIp() {
    return $this->ip;
  }

  public function getFullname() {
    return $this->fullname;
  }
}