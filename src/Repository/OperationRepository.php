<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
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
            ->getOneOrNullResult()
            ;
    }

    public function findByPharmacyIdAndCustomerIdOrderByCreatedAtAsc($pharmacyId, $customerId)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.pharmacy = :pharmacy_id')
            ->andWhere('o.customer = :customer_id')
            ->setParameter('pharmacy_id', $pharmacyId)
            ->setParameter('customer_id', $customerId)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Operation[] Returns an array of Operation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Operation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
