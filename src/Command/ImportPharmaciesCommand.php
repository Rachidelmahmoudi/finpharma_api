<?php

namespace App\Command;

use App\Service\PharmacyImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import-pharmacies',
    description: 'Import pharmacies from JSON files'
)]
class ImportPharmaciesCommand extends Command
{
    protected static $defaultName = 'app:import:pharmacies';

    public function __construct(private PharmacyImporter $importer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import pharmacies from a JSON file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');

        $count = $this->importer->importFromJsonFile($filePath);

        $output->writeln("✔ Imported {$count} pharmacies");

        return Command::SUCCESS;
    }
}
