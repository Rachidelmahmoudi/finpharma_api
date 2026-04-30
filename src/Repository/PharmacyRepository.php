<?php

namespace App\Repository;

use App\Entity\Pharmacy;
use App\Util\Searchable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Common\Collections\Criteria;

/**
 * @extends ServiceEntityRepository<Pharmacy>
 */
class PharmacyRepository extends ServiceEntityRepository implements Searchable
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pharmacy::class);
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
            return $this->createQueryBuilder('p')
            ->getQuery();
        }
        return $this->createQueryBuilder('p')
            ->andWhere('p.name like :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery();
    }


    public function findPharmacies(int $page = 1, int $itemsPerPage = 5, array $filter): DoctrinePaginator
    {
        $qb =  $this->createQueryBuilder('p')
        ->leftJoin('p.openingHours', 'oh')
        ->andWhere('oh.source = :source OR p.isAlwaysOpen = true')
        ->setParameter('source', 'scraper');

        if (!empty($filter['city'])) {
            $qb->andWhere('LOWER(p.city) = :city')
               ->setParameter('city', strtolower($filter['city']));
        }

        if (!empty($filter['town'])) {
            $qb->andWhere('LOWER(p.address) like :town OR LOWER(oh.town) = :town OR LOWER(p.town) = :town')
               ->setParameter('town', strtolower($filter['town']));
        }

        if (!empty($filter['name'])) {
            $qb->andWhere('LOWER(p.name) like :name')
               ->setParameter('name', '%'.strtolower($filter['name']).'%');
        }     

        if (!empty($filter['raduis'])) {
            $qb->addSelect('(6371000 * acos(cos(radians(:lat)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(p.latitude)))) AS distance')
                ->andWhere('(6371000 * acos(cos(radians(:lat)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(p.latitude)))) <= :radius')
                ->setParameter('lat', $filter['latitude'])
                ->setParameter('lng', $filter['longitude'])
                ->setParameter('radius', $filter['raduis'] * 1000)
                ->orderBy('distance', 'ASC');
        }


        return new DoctrinePaginator(
        $qb->addCriteria(
                    Criteria::create()
                        ->setFirstResult(($page - 1) * $itemsPerPage)
                        ->setMaxResults($itemsPerPage)
        ));
    }

    public function findNearestPharmacies(int $page = 1, int $itemsPerPage = 5, array $filter): DoctrinePaginator
    {
        $qb =  $this->createQueryBuilder('p');

        if (empty($filter['raduis']) || empty($filter['latitude']) || empty($filter['longitude'])) {
            throw new \Exception('Invalid arguments');
        }

        if (!empty($filter['raduis'])) {
            $qb->addSelect('(6371000 * acos(cos(radians(:lat)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(p.latitude)))) AS distance')
                ->andWhere('(6371000 * acos(cos(radians(:lat)) * cos(radians(p.latitude)) * cos(radians(p.longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(p.latitude)))) <= :radius')
                ->setParameter('lat', $filter['latitude'])
                ->setParameter('lng', $filter['longitude'])
                ->setParameter('radius', $filter['raduis'] * 1000)
                ->orderBy('distance', 'ASC');
        }
        return new DoctrinePaginator(
            $qb->addCriteria(Criteria::create()->setFirstResult(($page - 1) * $itemsPerPage)->setMaxResults($itemsPerPage))
        );
    }

    public function findPharmacyByCity(string $name, string $city): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.name) like :name AND LOWER(p.address) like :city')
            ->setParameter('name', '%'. trim(strtolower($name)).'%')
            ->setParameter('city', '%'.trim(strtolower($city)).'%')
            ->getQuery()
            ->getResult();
    }
}
