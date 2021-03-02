<?php

namespace App\Service\Application;

use App\Service\Domain\CustomerService;
use App\Service\Domain\OperationService;
use App\Service\Domain\PharmacyService;
use DateTime;

class SearchService
{
    private PharmacyService  $pharmacyService;
    private OperationService $operationService;
    private CustomerService $customerService;

    public function __construct(
        PharmacyService $pharmacyService,
        OperationService $operationService,
        CustomerService $customerService
    ) {
        $this->pharmacyService  = $pharmacyService;
        $this->operationService = $operationService;
        $this->customerService = $customerService;
    }

    public function getPharmacyCurrentBalanceBetweenTwoDates(
        int $pharmacyId,
        DateTime $startDate,
        DateTime $endDate
    ): int {
        $pharmacy = $this->pharmacyService->getPharmacy($pharmacyId);

        return $this->operationService->getPharmacyCurrentBalanceBetweenTwoDates($pharmacy, $startDate, $endDate);
    }

    public function getPharmacyCustomerTotalGivenPoints(
        int $pharmacyId,
        int $customerId
    ): int {
        $pharmacy = $this->pharmacyService->getPharmacy($pharmacyId);
        $customer = $this->customerService->getCustomer($customerId);

        return $this->operationService->getPharmacyCustomerTotalGivenPoints($pharmacy, $customer);
    }

    public function getCustomerAvailablePoints(
        int $customerId
    ) : int {
        $customer = $this->customerService->getCustomer($customerId);

        return $this->operationService->getCustomerAvailablePoints($customer);
    }
}