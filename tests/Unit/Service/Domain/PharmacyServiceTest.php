<?php

namespace App\Tests\Unit\Service\Domain;

use App\Entity\Pharmacy;
use App\Exception\PharmacyNotFound;
use App\Repository\PharmacyRepository;
use App\Service\Domain\PharmacyService;
use Prophecy\PhpUnit\ProphecyTrait;
use PHPUnit\Framework\TestCase;

class PharmacyServiceTest extends TestCase
{
    use ProphecyTrait;

    private PharmacyService $pharmacyService;
    /** @var \Prophecy\Prophecy\ObjectProphecy | PharmacyRepository */
    private $pharmacyFakeRepository;
    private Pharmacy $pharmacy;

    protected function setUp(): void
    {
        $this->pharmacyFakeRepository = $this->prophesize(PharmacyRepository::class);
        $this->pharmacy = (new PharmacyTestBuilder())->withName("PHARMACY");
    }

    protected function tearDown(): void
    {
        unset($this->pharmacyService, $this->pharmacyFakeRepository, $this->pharmacy);
    }

    /** @test */
    public function testGetPharmacy()
    {
        $this->pharmacyFakeRepository->findOneByPharmacyId(1)
            ->willReturn($this->pharmacy);

        $this->pharmacyService = new PharmacyService($this->pharmacyFakeRepository->reveal());

        $pharmacy = $this->pharmacyService->getPharmacy(1);
        self::assertEquals($this->pharmacy, $pharmacy);
    }

    /** @test */
    public function testGetPharmacyException()
    {
        $this->pharmacyFakeRepository->findOneByPharmacyId(1)
            ->willThrow(new PharmacyNotFound('Customer does not exists.'));

        $this->pharmacyService = new PharmacyService($this->pharmacyFakeRepository->reveal());

        self::expectException(PharmacyNotFound::class);
        $this->pharmacyService->getPharmacy(1);
    }
}