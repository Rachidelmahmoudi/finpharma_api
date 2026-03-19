<?php 

namespace App\Service;

use App\DBAL\EstablishmentType;
use App\Entity\Establishment;
use App\Entity\User;
use App\Repository\EstablishmentRepository;
use App\Repository\PharmacyRepository;

class EstablishmentService {
    public function __construct(private PharmacyRepository $pharmacy_repository)
    {
        
    }

    public function getEstablishmentsByUser(User $user, ?string $type): mixed
    {
       switch ($type) {
            case EstablishmentType::PHARMACY:
                $pharmacy_id = $user->getEstablishments()->filter(function (Establishment $est) {
                    return $est->getType() === EstablishmentType::PHARMACY;
                })->first()?->getTarget() ?? null;
                if (!$pharmacy_id) {
                    return null;
                }
                return $this->pharmacy_repository->find($pharmacy_id);
            default:
                throw new \InvalidArgumentException("Unsupported type");
        }
        return $user->getEstablishments();
    }
}