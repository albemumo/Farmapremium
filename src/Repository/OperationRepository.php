<?php

namespace App\Repository;

use App\Entity\Operation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Operation::class);
    }

    public function persist(Operation $operation): void
    {
        $this->em->persist($operation);
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function persistAndFlush(Operation $operation): Operation
    {
        $this->persist($operation);
        $this->flush();

        return $operation;
    }

    public function findByPharmacyIdAndCustomerIdAvailablePoints($pharmacyId, $customerId)
    {
        return $this->createQueryBuilder('o')
                ->select('SUM(o.remaining_points) AS total')
                ->andWhere('o.pharmacy = :pharmacy_id')
                ->andWhere('o.customer = :customer_id')
                ->setParameter('pharmacy_id', $pharmacyId)
                ->setParameter('customer_id', $customerId)
                ->getQuery()
                ->getSingleScalarResult()
            ?: 0;
    }

    public function findByPharmacyIdAndCustomerIdOrderByCreatedAtAsc($pharmacyId, $customerId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.pharmacy = :pharmacy_id')
            ->andWhere('o.customer = :customer_id')
            ->andWhere('o.remaining_points > 0')
            ->setParameter('pharmacy_id', $pharmacyId)
            ->setParameter('customer_id', $customerId)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByPharmacyIdAndBetweenTwoDatesCurrentBalance(
        $pharmacyId,
        DateTime $startDate,
        DateTime $endDate
    ) {
        return $this->createQueryBuilder('o')
                ->select('SUM(o.remaining_points) AS total')
                ->andWhere('o.pharmacy = :pharmacy_id')
                ->andWhere('o.remaining_points > 0')
                ->andWhere('o.createdAt BETWEEN :startDate and :endDate')
                ->setParameter('pharmacy_id', $pharmacyId)
                ->setParameter('startDate', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('endDate', $endDate->format('Y-m-d H:i:s'))
                ->getQuery()
                ->getSingleScalarResult()
        ?: 0;
    }

    public function findByPharmacyIdAndCustomerIdTotalGivenPoints($pharmacyId, $customerId)
    {
        return $this->createQueryBuilder('o')
                ->select('SUM(o.points) AS total')
                ->andWhere('o.pharmacy = :pharmacy_id')
                ->andWhere('o.customer = :customer_id')
                ->setParameter('pharmacy_id', $pharmacyId)
                ->setParameter('customer_id', $customerId)
                ->getQuery()
                ->getSingleScalarResult()
        ?: 0;
    }

    public function findByCustomerIdAvailablePoints($customerId)
    {
        return $this->createQueryBuilder('o')
                ->select('SUM(o.remaining_points) AS total')
                ->andWhere('o.customer = :customer_id')
                ->setParameter('customer_id', $customerId)
                ->getQuery()
                ->getSingleScalarResult()
        ?: 0;
    }
}
