<?php

namespace App\Tests\Unit\Service\Domain;

use App\Entity\Customer;
use App\Entity\Operation;
use App\Entity\Pharmacy;
use DateTime;
use PHPUnit\Framework\TestCase;

class OperationTestBuilder extends TestCase
{
    public const SAMPLE_FULLNAME = "SAMPLE_FULLNAME";

    private int      $points;
    private int      $remainingPoints;
    private Pharmacy $pharmacy;
    private Customer $customer;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct()
    {
        $datetime              = new DateTime();
        $this->points          = 100;
        $this->remainingPoints = 100;
        $this->pharmacy        = (new PharmacyTestBuilder())->withName('NAME');
        $this->customer        = (new CustomerTestBuilder())->withFullName('FULLNAME');
        $this->createdAt       = $datetime;
        $this->updatedAt       = $datetime;
    }

    public function build(): Operation
    {
        return new Operation($this->points, $this->remainingPoints, $this->pharmacy, $this->customer, $this->createdAt, $this->updatedAt);
    }

    public function withAllProps(
        int $points,
        int $remainingPoints,
        Pharmacy $pharmacy,
        Customer $customer,
        DateTime $createdAt,
        DateTime $updatedAt
    ): Operation {
        $this->points          = $points;
        $this->remainingPoints = $remainingPoints;
        $this->pharmacy        = $pharmacy;
        $this->customer        = $customer;
        $this->createdAt       = $createdAt;
        $this->updatedAt       = $updatedAt;

        return $this->build();
    }
}
