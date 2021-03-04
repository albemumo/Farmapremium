<?php

namespace App\Tests\Unit\Service\Domain;

use App\Entity\Customer;
use App\Entity\Operation;
use App\Entity\Pharmacy;
use App\Repository\OperationRepository;
use App\Service\Domain\OperationService;
use DateTime;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OperationServiceTest extends KernelTestCase
{
    use ProphecyTrait;

    private OperationService $operationService;
    /** @var \Prophecy\Prophecy\ObjectProphecy | OperationRepository */
    private           $operationFakeRepository;
    private Operation $operation;
    private DateTime  $fakeDatetime;

    protected function setUp(): void
    {
        $this->fakeDatetime            = new DateTime();
        $this->operationFakeRepository = $this->prophesize(OperationRepository::class);
        $this->operation               = (new OperationTestBuilder())->withAllProps(100, 100, new Pharmacy('name'),
            new Customer('fullname'), $this->fakeDatetime, $this->fakeDatetime);
    }

    protected function tearDown(): void
    {
        unset($this->operationService, $this->operationFakeRepository, $this->operation, $this->fakeDatetime);
    }

    public function testCreateOperation()
    {
        $operationService = new OperationService($this->operationFakeRepository->reveal());

        $operation = $operationService->createOperation(100, 100, new Pharmacy('name'), new Customer('fullname'),
            $this->fakeDatetime, $this->fakeDatetime);

        self::assertEquals($this->operation, $operation, "Operation does not match! ");
    }

    public function set($entity, $value, $propertyName = 'id')
    {
        $class    = new ReflectionClass($entity);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($entity, $value);
    }
}