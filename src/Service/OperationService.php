<?php

namespace App\Service;

use App\Entity\Operation;
use App\Repository\CustomerRepository;
use App\Repository\OperationRepository;
use App\Repository\PharmacyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OperationService {

    private $em;
    private $pharmacyRepository;
    private $customerRepository;
    private $operationRepository;

    public function __construct(EntityManagerInterface $entityManager, PharmacyRepository $pharmacyRepository, CustomerRepository $customerRepository, OperationRepository $operationRepository)
    {
        $this->em = $entityManager;
        $this->pharmacyRepository = $pharmacyRepository;
        $this->customerRepository = $customerRepository;
        $this->operationRepository = $operationRepository;
    }

    public function accumulate(int $pharmacy_id, int $customer_id, int $points): Operation
    {
        $pharmacy = $this->pharmacyRepository->find($pharmacy_id);
        $customer = $this->customerRepository->find($customer_id);

        $operation = new Operation($points, $points, $pharmacy, $customer);
        $this->em->persist($operation);
        $this->em->flush();

        return $operation;
    }

    public function withdraw(int $pharmacy_id, int $customer_id, int $points): Operation
    {
        $available_points = $this->operationRepository->findByPharmacyIdAndCustomerIdAvailablePoints($pharmacy_id, $customer_id);

        if ($points > $available_points['total']) {
            throw new HttpException(400, "Not enough points.");
        }

        $operations = $this->operationRepository->findByPharmacyIdAndCustomerIdOrderByCreatedAtAsc($pharmacy_id, $customer_id);


        dd($operations);
    }
}