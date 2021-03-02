<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Pharmacy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    const TOTAL_PHARMACIES = 5;
    const TOTAL_CUSTOMERS  = 10;

    public function load(ObjectManager $manager)
    {
        // Create Pharmacies
        for ($i = 1; $i <= AppFixtures::TOTAL_PHARMACIES; $i++) {
            $pharmacy = new Pharmacy('Pharmacy ' . $i);
            $manager->persist($pharmacy);
        }
        // Create Customers
        for ($i = 1; $i <= AppFixtures::TOTAL_CUSTOMERS; $i++) {
            $customer = new Customer('Customer ' . $i);
            $manager->persist($customer);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }



}
