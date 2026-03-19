<?php

namespace App\Repository;

use App\Entity\OpenPharmacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OpenPharmacy>
 */
class OpenPharmacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OpenPharmacy::class);
    }
    

    //    /**
    //     * @return OpenPharmacy[] Returns an array of OpenPharmacy objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OpenPharmacy
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByDay($day): array|OpenPharmacy|null {
        return $this->createQueryBuilder('o')
            ->andWhere('o.day = :day')
            ->setParameter('day', $day)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
