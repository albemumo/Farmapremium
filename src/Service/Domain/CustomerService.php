<?php

namespace App\Service\Domain;

use App\Entity\Customer;
use App\Repository\CustomerRepository;

class CustomerService
{
    private CustomerRepository $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    public function getCustomer(
        int $customerId
    ): Customer {
        return $this->customerRepository->findOneByCustomerId($customerId);
    }
}