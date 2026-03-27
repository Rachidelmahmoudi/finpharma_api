<?php

namespace App\Service;

use App\Entity\Pharmacy;
use Doctrine\ORM\EntityManagerInterface;
use LongitudeOne\Spatial\PHP\Types\Geography\Point as GeographyPoint;
use App\Repository\PharmacyRepository;

class PharmacyImporter
{
    public function __construct(private EntityManagerInterface $em, private readonly PharmacyRepository $phar) {
        
    }

    public function importFromJsonFile(string $filePath): int
    {
        //52
        // $pharmacy = $this->em->getRepository(Pharmacy::class)
        //     ->find(52);

        //dd($this->phar->findNearby2(31.565697, -8.076284, 10000));
        
        $failedFile = '/var/failed_imports.json';
        if (!file_exists($failedFile)) {
            file_put_contents($failedFile, "[]");
        }

        if (!file_exists($filePath)) {
            throw new \RuntimeException("JSON file not found: $filePath");
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \RuntimeException("Invalid JSON format.");
        }

        $batchSize = 20; // flush every 100 pharmacies
        $count = 0;

        foreach ($data as $item) {
            $hasTooLongField = false;
            foreach ($item as $key => $value) {
                if (is_string($value) && strlen($value) > 255) {
                    //dd("FIELD TOO LONG: $key => " . implode(",", $item));
                    $hasTooLongField = true;
                    break;
                }
            }
            if ($hasTooLongField) {
                $this->appendFailedImport($failedFile, $item);
                continue; // Skip this record safely
            }
            $this->createOrUpdatePharmacy($item);
            $count++;

            if (($count % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear(); // IMPORTANT: clear to free memory
            }
        }

        // Flush remaining entities
        $this->em->flush();
        $this->em->clear();

        return $count;
    }


    private function createOrUpdatePharmacy(array $item): void
    {
        $pharmacy = $this->em->getRepository(Pharmacy::class)
            ->findOneBy(['reference' => $item['reference'] ?? null]);

        if (!$pharmacy) {
            $pharmacy = new Pharmacy();
        }

        $pharmacy->setName($item['nom'] ?? null);
        $pharmacy->setAddress($item['adresse'] ?? null);
        $pharmacy->setPhone($item['telephone'] ?? null);
        $pharmacy->setCity($item['ville'] ?? null);
        $pharmacy->setReference($item['reference'] ?? null);
        $pharmacy->setCategory($item['category'] ?? null);
        $pharmacy->setGoogleMapsUrl($item['lien_google_maps'] ?? null);

        // 👉 Extract lat/lng
        [$lat, $lng] = $this->extractCoordinates($item['googleMapsUrl'] ?? null);
        try {
            //$pharmacy->setLocation(new GeographyPoint($lng, $lat));
            $pharmacy->setLatitude($lat);
            $pharmacy->setLongitude($lng);
        } catch (\Exception $ex) {
            
        }
        $this->em->persist($pharmacy);
    }

    private function extractCoordinates(?string $url): array
    {
        if (!$url) {
            return [null, null];
        }

        $parts = parse_url($url);

        if (!isset($parts['query'])) {
            return [null, null];
        }

        parse_str($parts['query'], $query);

        // Expected: q=30.42110062,-9.58306026
        if (!isset($query['q'])) {
            return [null, null];
        }

        $coords = explode(',', $query['q']);

        if (count($coords) !== 2) {
            return [null, null];
        }

        $lat = floatval($coords[0]);
        $lng = floatval($coords[1]);

        return [$lat, $lng];
    }

    private function appendFailedImport(string $filePath, array $item): void
    {
        $failed = json_decode(file_get_contents($filePath), true);

        if (!is_array($failed)) {
            $failed = [];
        }

        $failed[] = $item;

        // Write back pretty JSON
        file_put_contents(
            $filePath,
            json_encode($failed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

}
