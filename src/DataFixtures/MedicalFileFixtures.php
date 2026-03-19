<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Factory\MedicalFileFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MedicalFileFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        MedicalFileFactory::createMany(50);
        //$manager->flush();
    }

    public function getDependencies() : array {
        return [
            PatientFixtures::class
        ];
    }
}
