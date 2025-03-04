<?php

namespace App\Repository;

use App\Entity\Pharmacy;
use App\Exception\PharmacyNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Pharmacy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pharmacy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pharmacy[]    findAll()
 * @method Pharmacy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PharmacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pharmacy::class);
    }

    public function findOneByPharmacyId(int $pharmacyId): Pharmacy
    {
        $pharmacy = $this->findOneBy(['id' => $pharmacyId]);
        if (!$pharmacy) {
            throw new PharmacyNotFound(sprintf('The pharmacy with id %d does not exist.', $pharmacyId));
        }
        return $pharmacy;
    }
}
