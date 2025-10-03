<?php

namespace Omnipay\Antilopay\Model;

readonly class CustomerReference
{
    public function __construct(
        public string $email,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $ip = null,
        public ?string $fullname = null,
    ) {}
}