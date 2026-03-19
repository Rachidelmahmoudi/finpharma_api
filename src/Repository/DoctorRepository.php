<?php

namespace App\Repository;

use App\Entity\Doctor;
use App\Util\Searchable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Doctor>
 */
class DoctorRepository extends ServiceEntityRepository implements Searchable
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    /**
     * Search by query text
     * 
     * @param string $query
     * 
     * @return mixed
     */
    public function search(string $query): mixed
    {
        if (empty($query)) {
            return $this->createQueryBuilder('d')
            ->getQuery();
        }
        return $this->createQueryBuilder('d')
            ->andWhere('d.name like :query OR d.description like :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery();
    }
}
