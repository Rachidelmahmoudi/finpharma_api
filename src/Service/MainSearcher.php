<?php

namespace App\Service;

use App\Entity\Doctor;
use App\Entity\Laboratory;
use App\Entity\Pharmacy;
use App\Form\SearchTypes;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

class MainSearcher {
    public function __construct(private readonly EntityManagerInterface $entity_manager)
    {
        
    }

    public function search(string $type, string $query): null | Query {
        $subject = $this->getSubject($type);
        if ($subject  === 0) {
            $subject = Doctor::class;
        }
        return $this->entity_manager->getRepository($subject)->search($query);
    }

    private function getSubject(string $type) : mixed {
        return match($type) {
            SearchTypes::PHARAMACIES->value => Pharmacy::class,
            SearchTypes::LABORATORIES->value => Laboratory::class,
            SearchTypes::MEDECINE->value => Doctor::class,
            default => 0,
        };
    }

    public function searchAll() {
        $rsm = new ResultSetMapping();
        $pharmacies_query = 'SELECT name, address, city, type as P FROM pharmacies';
        $laboratories_query = 'SELECT name, address, city, description, type as L FROM laboratory';
        $doctors_query = 'SELECT name, address, city, description, type as D FROM doctor';
        $query = $this->entity_manager->createNativeQuery('', $rsm);
        $query->setParameter(1, 'romanb');
    }
}