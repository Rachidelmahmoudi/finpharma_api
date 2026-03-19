<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\Doctrine\Orm\Paginator;
use App\DBAL\EstablishmentType;
use App\Entity\Establishment;
use App\Entity\Pharmacy;
use App\Repository\PharmacyRepository;
use App\Service\EstablishmentService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use App\Entity\User;

class MyPharmacyProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private EstablishmentService $establishment_service
    ) {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }
        
        return $this->establishment_service->getEstablishmentsByUser($user, EstablishmentType::PHARMACY);
    }
}
