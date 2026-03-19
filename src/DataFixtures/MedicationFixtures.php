<?php

namespace App\DataFixtures;

use App\Factory\MedicationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MedicationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        MedicationFactory::createMany(3);
        //$manager->flush();
    }
}
