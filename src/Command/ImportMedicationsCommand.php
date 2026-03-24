<?php

namespace App\Command;

use App\Entity\Medication;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function PHPSTORM_META\map;

#[AsCommand(
    name: 'app:import-medications',
    description: 'Import pharmacies from JSON files'
)]
class ImportMedicationsCommand extends Command
{
    protected static $defaultName = 'app:import:medications';

    public function __construct(private readonly ParameterBagInterface $parameter_bag, private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $this->parameter_bag->get('kernel.project_dir') . '/public/data/medications/';
        for ($i = ord('A'); $i <= ord('Z'); $i++) {
            $letter = chr($i);
            $data = file_get_contents($path . 'medications_' . $letter . '.json');
            $medications = json_decode($data, true);
            foreach ($medications as $medication) {
                try {
                    $m = new Medication();
                    $price = str_replace(' dhs', '', ($medication['price'] ?? ''));
                    $hospitalPrice = str_replace(' dhs', '', ($medication['hospitalPrice'] ?? ''));
                    $nature = match($medication['Nature du Produit'] ?? null) {
                        'Médicament' => 1,
                        'Complément alimentaire' => 2,
                        default => 0
                    };
                    $status = match($medication['status'] ?? null) {
                        'Commercialisé' => 1,
                        default => 0
                    };
                    $m->setName(strlen($medication['name']) > 100 ? '' : $medication['name'])
                    ->setComposition(strlen($medication['composition']) > 255 ? '' : ($medication['composition'] ?? ''))
                    ->setDosage(strlen($medication['dosage']) > 100 ? '' : ($medication['dosage'] ?? null))
                    ->setHospitalPrice(floatval($hospitalPrice))
                    ->setNature($nature)
                    ->setIndications($medication['indications'] ?? null)
                    ->setContraindications($medication['Contres-indication(s)'] ?? null)
                    ->setPrice(floatval($price))
                    ->setStatus($status)
                    ->setPresentation(strlen($medication['presentation']) > 100 ? '' : ($medication['presentation'] ?? null))
                    ->setManufacturer($medication['manufacturer'] ?? null)
                    ->setIsRecalled(false)
                    ->setIsRefundable(true)
                    ->setCanPregnancy($medication['Grossesse'] ?? null);
                    $this->em->persist($m);
                }
                catch(\Exception $ex) {
                    continue;
                }
            }
            $this->em->flush();
            $output->writeln("✔ Imported {$letter} letter");
        }

        return Command::SUCCESS;
    }
}
