<?php

namespace App\Tests\Unit\Service\Domain;

use App\Entity\Customer;
use App\Exception\CustomerNotFound;
use App\Repository\CustomerRepository;
use App\Service\Domain\CustomerService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CustomerServiceTest extends TestCase
{
    use ProphecyTrait;

    private CustomerService $customerService;
    /** @var \Prophecy\Prophecy\ObjectProphecy | CustomerRepository */
    private          $customerFakeRepository;
    private Customer $customer;

    protected function setUp(): void
    {
        $this->customerFakeRepository = $this->prophesize(CustomerRepository::class);
        $this->customer         = (new CustomerTestBuilder())->withFullName('PAQUITO');
    }

    protected function tearDown(): void
    {
        unset($this->customerService, $this->customerFakeRepository, $this->customer);
    }

    /** @test */
    public function testGetCustomer()
    {
        $this->customerFakeRepository->findOneByCustomerId(1)
            ->willReturn($this->customer);

        $this->customerService = new CustomerService($this->customerFakeRepository->reveal());

        $customer = $this->customerService->getCustomer(1);
        self::assertEquals($this->customer, $customer, "Customer does not match! ");
    }

    /** @test */
    public function testGetCustomerException()
    {
        $this->customerFakeRepository->findOneByCustomerId(2)
            ->willThrow(new CustomerNotFound('Customer does not exists.'));

        $this->customerService = new CustomerService($this->customerFakeRepository->reveal());

        self::expectException(CustomerNotFound::class);
        $this->customerService->getCustomer(2);
    }
}

