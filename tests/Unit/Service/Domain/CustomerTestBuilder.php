<?php

namespace App\Tests\Unit\Service\Domain;

use App\Entity\Customer;

class CustomerTestBuilder
{
    public const SAMPLE_FULLNAME = "SAMPLE_FULLNAME";

    private string $fullName;

    public function __construct()
    {
        $this->fullName = self::SAMPLE_FULLNAME;
    }

    public function build(): Customer
    {
        return new Customer($this->fullName);
    }

    public function withFullName(string $fullName): Customer
    {
        $this->fullName = $fullName;

        return $this->build();
    }
}