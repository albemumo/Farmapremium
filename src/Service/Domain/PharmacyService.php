<?php

namespace App\Service\Domain;

use App\Entity\Pharmacy;
use App\Repository\PharmacyRepository;

class PharmacyService {

    private PharmacyRepository $pharmacyRepository;

    public function __construct(PharmacyRepository $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function getPharmacy(int $pharmacyId): Pharmacy
    {
        return $this->pharmacyRepository->findOneByPharmacyId($pharmacyId);
    }
}