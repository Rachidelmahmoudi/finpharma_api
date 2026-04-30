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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:import-open-pharmacies',
    description: 'Import open pharmacies from JSON files'
)]
class ImportOpenPharmaciesCommand extends Command
{
    protected static $defaultName = 'app:import-open-pharmacies';

    public function __construct(private EntityManagerInterface $em, private readonly ParameterBagInterface $parameter_bag)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonFile = $this->parameter_bag->get('kernel.project_dir') . '/public/data/open.json';
        $data = json_decode(file_get_contents($jsonFile), true);

        foreach ($data as $citiesData) {
            $city = $citiesData['city'] ?? null;
            $pharmaciesData = $citiesData['pharmacies'] ?? [];
            foreach ($pharmaciesData as $pharmacyData) {
                $pharmacyName = $pharmacyData['name'] ?? null;
                $status = $pharmacyData['status'] ?? null;
                $pharmacy = $this->em->getRepository(Pharmacy::class)->findOneBy([
                    'name' => $pharmacyName,
                    'city' => $city
                ]);
                if (!$pharmacy) {
                    $pharmacy = new Pharmacy();
                    $pharmacy->setName($pharmacyName)
                        ->setAddress($pharmacyData['district'] ?? 'Mon adresse inconnue')
                        ->setPhone('0600000000')
                        ->setTown($pharmacyData['district'])
                        ->setCity($city);
                    $this->em->persist($pharmacy);
                }
                if ($status === 'Ouvert en ce moment') {
                    $openPharmacy = new OpenPharmacy();
                    $openPharmacy->setPharmacy($pharmacy)
                        ->setTown($pharmacyData['district'])
                        ->setGardeStatus($status)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setSource('scraper');
                    
                    $this->em->persist($openPharmacy);
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
