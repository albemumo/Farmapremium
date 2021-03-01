<?php

namespace App\Service;

use App\Entity\Customer;
use App\Repository\CustomerRepository;

class CustomerService {

    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAvailablePoints()
    {

    }
}