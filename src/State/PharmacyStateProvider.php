<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pharmacy;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\Doctrine\Orm\Paginator;
use App\Repository\PharmacyRepository;

class PharmacyStateProvider implements ProviderInterface
{
    public function __construct(private readonly Pagination $pagination, private readonly PharmacyRepository $pharmacyRepo)
    {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        [$page, $offset, $limit] = $this->pagination->getPagination($operation, $context);
        $filter = $_REQUEST;
        return new Paginator($this->pharmacyRepo->findPharmacies($page, $limit, $filter));
    }
}
