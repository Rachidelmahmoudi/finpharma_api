<?php

namespace App\DataFixtures;

use App\Entity\Specialty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SpecialtyFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private readonly ParameterBagInterface $parameter)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $root_dir = $this->parameter->get('kernel.project_dir');
        $json = file_get_contents($root_dir . '/public/data/specialites.json');
        $data = json_decode($json, true);
        if (!$data) {
            return;
        }
        foreach ($data['specialites_medicales'] as $speciality) {
            $s = new Specialty();
            $s->setName($speciality);
            $manager->persist($s);
        }
        $manager->flush();
    }

    public static function getGroups(): array {
        return ['speciality'];
    }
}
