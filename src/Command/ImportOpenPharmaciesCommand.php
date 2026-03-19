<?php

namespace App\Command;

use App\Entity\OpenPharmacy;
use App\Entity\Pharmacy;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-open-pharmacies',
    description: 'Import open pharmacies from JSON files'
)]
class ImportOpenPharmaciesCommand extends Command
{
    protected static $defaultName = 'app:import-open-pharmacies';

    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonFile = './pharmacies_complete.json';
        $data = json_decode(file_get_contents($jsonFile), true);

        foreach ($data as $townData) {
            $city = $townData['name'] ?? null;
            foreach (($townData['neighborhoods'] ?? []) as $neighborhoodData) {
                $dutyType = $neighborhoodData['dutyType'] ?? null;

                foreach ($neighborhoodData['neighborhoods'] as $quartierData) {
                    $quartier = !empty($quartierData['quartier']) ? str_replace('Quartier: ', '', $quartierData['quartier']) : 'Other';
                    foreach ($quartierData['pharmacies'] as $pharmacyData) {
                        $pharmacyName = $pharmacyData['pharmacy'] ?? null;
                        $gardeStatus = $pharmacyData['garde_status'] ?? null;

                        if (!$pharmacyName) {
                            continue; // skip invalid data
                        }

                        // Find existing Pharmacy by name + city
                        $pharmacy = $this->em
                            ->getRepository(Pharmacy::class)
                            ->findPharmacyByCity($pharmacyName, $city);

                        $pharmacy = !empty($pharmacy) ?  $pharmacy[0] : null;

                        if (!$pharmacy) {
                            $output->writeln("Pharmacy not found: {$pharmacyName} in {$city}");
                            continue;
                        }
                        $openPharmacy = $this->em
                            ->getRepository(OpenPharmacy::class)
                            ->findOneBy([
                                'pharmacy' => $pharmacy->getId(),
                                'day' => new \DateTime(),
                                'source' => 'scraper'
                            ]);
                        if (!$openPharmacy) {
                            $openPharmacy = new OpenPharmacy();
                        }
                        $openPharmacy->setPharmacy($pharmacy);
                        $openPharmacy->setTown($quartier);
                        $openPharmacy->setDutyType($dutyType);
                        $openPharmacy->setCreatedAt(new \DateTimeImmutable('now'))->setUpdatedAt(new \DateTimeImmutable('now'));
                        $openPharmacy->setGardeStatus(str_replace('Garde Jour ','', $gardeStatus))
                        ->setDay(new \DateTime())
                        ->setSource('scraper');
                        if ($gardeStatus === "Ouvert en ce moment") {
                            $openPharmacy->setAmFrom(new \DateTime('now'))->setAmTo(new \DateTime('now +3 hours'));
                        }
                        if (str_contains($gardeStatus, '24h')) {
                            $openPharmacy->setAmFrom(new \DateTime('today 00:00'))->setAmTo(new \DateTime('today 23:59'));
                            $pharmacy->setIsAlwaysOpen(true);
                        }
                        preg_match('/(\d{1,2}h).*?(\d{1,2}h)/', $gardeStatus, $matches);
                        if (count($matches) < 2) {
                            continue; // skip if time format is not as expected
                        }
                        $start = $matches[1];
                        $end = $matches[2];
                        $start_hour = str_replace('h', ':00', $start);
                        $end_hour = str_replace('h', ':00', $end);
                        if (str_contains($gardeStatus, 'Garde Jour')) {
                            if (new DateTime($end_hour) > new DateTime('today 12:00')) {
                                $openPharmacy->setAmFrom(new \DateTime("today {$start_hour}"))->setAmTo(new \DateTime("today 12:00"));
                                $openPharmacy->setPmFrom(new \DateTime("today 13:00"))->setPmTo(new \DateTime("today {$end_hour}"));    
                            } else {
                                $openPharmacy->setAmFrom(new \DateTime("today {$start_hour}"))->setAmTo(new \DateTime("today {$end_hour}"));
                            }
                        } else if (str_contains($gardeStatus, 'Garde Nuit')) {
                            if (new DateTime($start_hour) > new DateTime($end_hour)) {
                                $openPharmacy->setPmFrom(new \DateTime("today {$start_hour}"))->setPmTo(new \DateTime("today 23:59"));
                                $openPharmacy->setAmFrom(new \DateTime("today 0:00"))->setAmTo(new \DateTime("today {$end_hour}"));
                            } else {
                                $openPharmacy->setPmFrom(new \DateTime("today {$start_hour}"))->setPmTo(new \DateTime("today {$end_hour}")); 
                            }
                        }
                        $this->em->persist($openPharmacy);
                    }
                }
            }
        }

        $this->em->flush();
        $output->writeln('Open pharmacies imported successfully.');

        return Command::SUCCESS;
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__, 2); // adjust depending on your structure
    }
}
