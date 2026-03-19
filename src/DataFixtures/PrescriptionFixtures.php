<?php

namespace App\DataFixtures;

use App\Entity\Consultation;
use App\Entity\Medication;
use App\Factory\PrescriptionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PrescriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        PrescriptionFactory::createMany(10);
        //$manager->flush();
    }

    public function getDependencies() : array {
        return [
            MedicationFixtures::class,
            ConsultationFixtures::class
        ];
    }
}
