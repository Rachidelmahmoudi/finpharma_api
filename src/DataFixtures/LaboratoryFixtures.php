<?php

namespace App\DataFixtures;

use App\Entity\AnalyseCategory;
use App\Factory\LaboratoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LaboratoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        LaboratoryFactory::createMany(100);

        //$manager->flush();
    }
}
