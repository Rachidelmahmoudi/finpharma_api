<?php
namespace App\Service;

use App\Dto\CreatePharmacy;
use App\Entity\OpenPharmacy;
use App\Entity\Pharmacy;
use App\Repository\OpenPharmacyRepository;
use App\State\OpenMode;
use Doctrine\ORM\EntityManagerInterface;

class PharmacyGardeSaver {
    public function __construct(private EntityManagerInterface $entity_manager, private OpenPharmacyRepository $open_pharmacy_repository)
    {
        
    }
    public function saveGards(CreatePharmacy $dtoData, Pharmacy $pharmacy): Pharmacy {
        foreach ($dtoData->gardeDays as $open) {
            $openPharmacy = $this->open_pharmacy_repository->findOneBy([
                'pharmacy' => $pharmacy,
                'day' => new \DateTime($open['date'])
            ]);
            if (!$openPharmacy) {
                $openPharmacy = new OpenPharmacy();
            }
            $amFrom = $open['openingHours']['from'] ?? $open['openingHours']['morning']['from'] ?? '00:00';
            $amTo = $open['openingHours']['to'] ?? $open['openingHours']['morning']['to'] ?? '00:00';
            $pmFrom = $open['openingHours']['afternoon']['from'] ?? '00:00';
            $pmTo = $open['openingHours']['afternoon']['to'] ?? '00:00';


            $openPharmacy->setTown($dtoData->town)
            ->setDutyType($open['type'] ?? 'custom')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDay(new \DateTime($open['date']))
            ->setAmFrom(new \DateTime($open['date'].' '.$amFrom))
            ->setAmTo(new \DateTime($open['date'].' '.$amTo))
            ->setPmFrom(new \DateTime($open['date'].' '.$pmFrom))
            ->setPmTo(new \DateTime($open['date'].' '.$pmTo));
            $this->entity_manager->persist($openPharmacy);
            $pharmacy->addOpeningHour($openPharmacy);
        }
        return $pharmacy;
    }
}