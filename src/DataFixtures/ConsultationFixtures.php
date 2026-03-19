<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\MedicalFile;
use App\Factory\ConsultationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConsultationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
       ConsultationFactory::createMany(100);
    }

    public function getDependencies() : array {
        return [
            MedicalFileFixtures::class,
            DoctorFixtures::class
        ];
    }
}
