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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $IsNewPharmacy = false;
        $pharmacy = new Pharmacy();
        $est = new Establishment();
        if ($currentUser->getEstablishments()->filter(fn (Establishment $est) => $est->getType() === EstablishmentType::PHARMACY)->count() > 0) {
            $ests = $currentUser->getEstablishments()->filter(fn (Establishment $est) => $est->getType() === EstablishmentType::PHARMACY);
            $est = $ests->first();
            $pharmacyId = $est->getTarget();
            if (!empty($pharmacyId)) {
                if ($operation instanceof Put && $uriVariables['id'] !== $pharmacyId) {
                    throw new AccessDeniedHttpException('Inavlid pharmacy');
                }
                $pharmacy = $this->entity_manager->getRepository(Pharmacy::class)->find($pharmacyId);
                if (!$pharmacy) {
                    throw new AccessDeniedHttpException('Inavlid pharmacy');
                }
            }
            $pharmacy = $this->createPharmacy($pharmacy, $dtoData);
        } else {
            $IsNewPharmacy = true;
            $pharmacy = $this->createPharmacy($pharmacy, $dtoData);
            $this->entity_manager->persist($pharmacy);
            $est->setType(EstablishmentType::PHARMACY)
            ->setTarget($pharmacy->getId());
            $est->addHandler($currentUser);
        }

        if (!$dtoData->isAlwaysOpen) {
            $pharmacy = $this->pharmacyGardeSaver->saveGards($dtoData, $pharmacy);
        }

        $this->entity_manager->persist($est);
        $this->entity_manager->persist($pharmacy);
        $this->entity_manager->persist($currentUser);

        $this->entity_manager->flush();

        if ($IsNewPharmacy) {
            $this->pharmacy_password_link->sendLink($currentUser, $dtoData->lang);
        }

        return $pharmacy;
    }

    private function createPharmacy(Pharmacy $pharmacy, mixed $dtoData) : Pharmacy
    {
         $pharmacy->setName($dtoData->name)
            ->setCity($dtoData->city)
            ->setAddress($dtoData->address)
            ->setLatitude($dtoData->latitude)
            ->setLongitude($dtoData->longitude)
            ->setPhone($dtoData->phone)
            ->setTown($dtoData->town ?? $dtoData->customTown)
            // ->setEmail($dtoData->email)
            ->setIsAlwaysOpen($dtoData->isAlwaysOpen);
            return $pharmacy;
    }
}
