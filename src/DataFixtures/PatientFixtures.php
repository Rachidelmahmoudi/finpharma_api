<?php

namespace App\DataFixtures;

use App\Factory\PatientFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PatientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        PatientFactory::createMany(100);
        //$manager->flush();
    }
}
