<?php

namespace App\Service\Application;

use App\Entity\Operation;
use App\Exception\NotEnoughPoints;
use App\Service\Domain\CustomerService;
use App\Service\Domain\OperationService;
use App\Service\Domain\PharmacyService;

class WithdrawService
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

    public function withdraw(
        int $pharmacyId,
        int $customerId,
        int $points
    ): int {
        $pharmacy = $this->pharmacyService->getPharmacy($pharmacyId);
        $customer = $this->customerService->getCustomer($customerId);

        $availablePoints = $this->operationService->getAvailablePointsByPharmacyAndCustomer($pharmacy, $customer);

        if ($points > $availablePoints) {
            throw new NotEnoughPoints('Not enough points.');
        }

        $operations = $this->operationService->getOperationsByPharmacyAndCustomerWithRemainingPoints($pharmacy,
            $customer);

        $totalRemainingPoints = $points;
        foreach ($operations as $operation) {
            $remainingPoints = $operation->getRemainingPoints() - $totalRemainingPoints;
            if ($remainingPoints >= 0) {
                $totalRemainingPoints = 0;
            }

            if ($remainingPoints < 0) {
                $remainingPoints      = 0;
                $totalRemainingPoints = $totalRemainingPoints - $operation->getRemainingPoints();
            }

            $this->operationService->persistRemainingPoints($operation, $remainingPoints);

            if ($totalRemainingPoints == 0) {
                break;
            }
        }
        $this->operationService->save();

        return $availablePoints - $points;
    }
}