<?php


namespace App\Tests\Unit\Service\Domain;


use App\Entity\Pharmacy;

class PharmacyTestBuilder
{
    public const SAMPLE_NAME = "SAMPLE_NAME";

    private string $name;

    public function __construct()
    {
        $this->name = self::SAMPLE_NAME;
    }

    public function build(): Pharmacy
    {
        return new Pharmacy($this->name);
    }

    public function withName(string $name): Pharmacy
    {
        $this->name = $name;

        return $this->build();
    }
}