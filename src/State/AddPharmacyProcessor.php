<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\DBAL\EstablishmentType;
use App\Dto\CreatePharmacy;
use App\Entity\Establishment;
use App\Entity\Pharmacy;
use App\Entity\User;
use App\Service\PharmacyGardeSaver;
use App\Service\PharmacyPasswordLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddPharmacyProcessor implements ProcessorInterface
{
    public function __construct(
        private PharmacyGardeSaver $pharmacyGardeSaver,
        private EntityManagerInterface $entity_manager,
        private PharmacyPasswordLink $pharmacy_password_link,
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        
    }
    
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /**
         * @var CreatePharmacy $dtoData
         */
        $dtoData = $data;

        /** @var User|null $currentUser */
        $currentUser = $this->security->getUser();
        $currentUser->setRoles([USER::ROLE_PHARMACY_ADMIN, User::ROLE_USER]);

        if ($operation instanceof Put) {
            if (!$currentUser instanceof User) {
                throw new \RuntimeException('Authentication required');
            }

            $pharmacyId = $currentUser->getEstablishments()
                ->filter(fn (Establishment $est) => $est->getType() === EstablishmentType::PHARMACY)
                ->first()
                ?->getTarget();

            if (!$pharmacyId) {
                throw new \RuntimeException('No pharmacy establishment linked to current user');
            }

            $pharmacy = $this->entity_manager->getRepository(Pharmacy::class)->find($pharmacyId);
            if (!$pharmacy instanceof Pharmacy) {
                throw new \RuntimeException('Pharmacy not found');
            }
        } else {
            // POST: create pharmacy + invited admin user + establishment link
            $pharmacy = new Pharmacy();
            $this->entity_manager->persist($pharmacy);
            $this->entity_manager->flush(); // ensure ULID is generated before linking establishment

            $est = new Establishment();
            $est->setType(EstablishmentType::PHARMACY)
                ->setTarget($pharmacy->getId());
            $est->addHandler($currentUser);
            $this->entity_manager->persist($est);
        }

        // Shared: map DTO to pharmacy entity
        $pharmacy->setName($dtoData->name)
            ->setCity($dtoData->city)
            ->setAddress($dtoData->address)
            ->setLatitude($dtoData->latitude)
            ->setLongitude($dtoData->longitude)
            ->setPhone($dtoData->phone)
            ->setTown($dtoData->town ?? $dtoData->customTown)
            // ->setEmail($dtoData->email)
            ->setIsAlwaysOpen($dtoData->isAlwaysOpen);
        
        if (!$dtoData->isAlwaysOpen) {
            $pharmacy = $this->pharmacyGardeSaver->saveGards($dtoData, $pharmacy);
        }

        $this->entity_manager->flush();

        if ($operation instanceof Post) {
            $this->pharmacy_password_link->sendLink($currentUser, $dtoData->lang);
        }

        return $pharmacy;
    }
}
