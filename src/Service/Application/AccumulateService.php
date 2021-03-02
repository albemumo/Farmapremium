<?php

namespace App\Service\Application;

use App\Entity\Operation;
use App\Service\Domain\CustomerService;
use App\Service\Domain\OperationService;
use App\Service\Domain\PharmacyService;

class AccumulateService
{
    private PharmacyService  $pharmacyService;
    private CustomerService  $customerService;
    private OperationService $operationService;

    public function __construct(
        PharmacyService $pharmacyService,
        CustomerService $customerService,
        OperationService $operationService
    ) {
        $this->pharmacyService  = $pharmacyService;
        $this->customerService  = $customerService;
        $this->operationService = $operationService;
    }

    public function accumulate(
        int $pharmacyId,
        int $customerId,
        int $points
    ): Operation {
        $pharmacy = $this->pharmacyService->getPharmacy($pharmacyId);
        $customer = $this->customerService->getCustomer($customerId);

        return $this->operationService->createOperation($points, $points, $pharmacy, $customer);
    }
}