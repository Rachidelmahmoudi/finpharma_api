<?php

namespace App\Repository;

use App\Entity\Laboratory;
use App\Util\Searchable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Laboratory>
 */
class LaboratoryRepository extends ServiceEntityRepository implements Searchable
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Laboratory::class);
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
            return $this->createQueryBuilder('l')
            ->getQuery();
        }
        return $this->createQueryBuilder('l')
            ->andWhere('l.name like :query OR l.description like :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery();
    }
}
