<?php

namespace App\Service;

use App\Entity\Pharmacy;
use App\Repository\PharmacyRepository;

class PharmacyService {

    private $pharmacyRepository;

    public function __construct(PharmacyRepository $pharmacyRepository)
    {
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function getPharmacy(int $pharmacy_id): Pharmacy
    {
        return $this->pharmacyRepository->find($pharmacy_id);
    }
}