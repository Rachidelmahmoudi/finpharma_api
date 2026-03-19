<?php

namespace App\DataFixtures;

use App\Entity\Specialty;
use App\Factory\DoctorFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DoctorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        DoctorFactory::createMany(100);

        //$manager->flush();
    }

    public function getDependencies(): array {
        return [
            SpecialtyFixtures::class
        ];
    }
}
