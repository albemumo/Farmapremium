<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Operation;
use App\Entity\Pharmacy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const TOTAL_PHARMACIES = 3;
    const TOTAL_CUSTOMERS  = 5;

    public function load(ObjectManager $manager)
    {
        // Create Pharmacies
        $pharmacies = [];
        for ($i = 1; $i <= AppFixtures::TOTAL_PHARMACIES; $i++) {
            $pharmacy = new Pharmacy('Pharmacy ' . $i);
            array_push($pharmacies, $pharmacy);
            $manager->persist($pharmacy);
        }

        // Create Customers
        $customers = [];
        for ($i = 1; $i <= AppFixtures::TOTAL_CUSTOMERS; $i++) {
            $customer = new Customer('Customer ' . $i);
            array_push($customers, $customer);
            $manager->persist($customer);
        }

        $operations = [];

        array_push($operations, new Operation(20, 0, $pharmacies[0], $customers[0]));
        array_push($operations, new Operation(15, 10, $pharmacies[0], $customers[0]));
        array_push($operations, new Operation(20, 20, $pharmacies[0], $customers[0]));
        array_push($operations, new Operation(10, 10, $pharmacies[0], $customers[1]));

        array_push($operations, new Operation(20, 0, $pharmacies[1], $customers[0]));
        array_push($operations, new Operation(15, 10, $pharmacies[1], $customers[1]));
        array_push($operations, new Operation(20, 20, $pharmacies[1], $customers[1]));
        array_push($operations, new Operation(10, 10, $pharmacies[1], $customers[2]));

        array_push($operations, new Operation(20, 0, $pharmacies[2], $customers[3]));
        array_push($operations, new Operation(15, 10, $pharmacies[2], $customers[3]));
        array_push($operations, new Operation(20, 20, $pharmacies[2], $customers[3]));

        array_push($operations, new Operation(20, 15, $pharmacies[2], $customers[2]));
        array_push($operations, new Operation(15, 15, $pharmacies[2], $customers[3]));
        array_push($operations, new Operation(20, 5, $pharmacies[2], $customers[4]));
        array_push($operations, new Operation(20, 20, $pharmacies[2], $customers[4]));

        // Create Operations
        for ($i = 0; $i < count($operations); $i++) {
            $manager->persist($operations[$i]);
        }

        $manager->flush();
    }


}
