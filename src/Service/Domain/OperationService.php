<?php

namespace App\Service\Domain;

use App\Entity\Customer;
use App\Entity\Operation;
use App\Entity\Pharmacy;
use App\Repository\OperationRepository;
use DateTime;

class OperationService
{

    private OperationRepository $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    public function createOperation(
        int $points,
        int $remainingPoints,
        Pharmacy $pharmacy,
        Customer $customer
    ): Operation {
        $operation = new Operation($points, $remainingPoints, $pharmacy, $customer);

        return $this->operationRepository->persistAndFlush($operation);
    }

    public function getAvailablePointsByPharmacyAndCustomer(
        Pharmacy $pharmacy,
        Customer $customer
    ) : int {
        return $this->operationRepository->findByPharmacyIdAndCustomerIdAvailablePoints($pharmacy->getId(), $customer->getId());
    }

    public function getOperationsByPharmacyAndCustomerWithRemainingPoints(
        Pharmacy $pharmacy,
        Customer $customer
    ) {
        return $this->operationRepository->findByPharmacyIdAndCustomerIdOrderByCreatedAtAsc($pharmacy->getId(), $customer->getId());
    }

    public function persistRemainingPoints(
        Operation $operation,
        $remainingPoints
    ): void {
        $operation = $operation->setRemainingPoints($remainingPoints);

        $this->operationRepository->persist($operation);
    }

    public function save(): void
    {
        $this->operationRepository->flush();
    }

    public function getPharmacyCurrentBalanceBetweenTwoDates(
        Pharmacy $pharmacy,
        DateTime $startDate,
        DateTime $endDate
    ): int {
        return $this->operationRepository->findByPharmacyIdAndBetweenTwoDatesCurrentBalance($pharmacy->getId(), $startDate, $endDate);
    }

    public function getPharmacyCustomerTotalGivenPoints(
        Pharmacy $pharmacy,
        Customer $customer
    ): int {
        return $this->operationRepository->findByPharmacyIdAndCustomerIdTotalGivenPoints($pharmacy->getId(), $customer->getId());
    }

    public function getCustomerAvailablePoints(
        Customer $customer
    ): int {
        return $this->operationRepository->findByCustomerIdAvailablePoints($customer->getId());
    }
}