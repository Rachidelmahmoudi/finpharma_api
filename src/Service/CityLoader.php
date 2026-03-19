<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CityLoader {
    public function __construct(private readonly ParameterBagInterface $parameter_bag)
    {
        
    }

    /**
     * Get cities from json file
     * 
     * @return array
     */
    public function getCities(): array {
        $file = $this->parameter_bag->get('kernel.project_dir') . '/public/data/cities.json';
        if (!file_exists($file)) {
            throw new Exception('The json file for cities is not found');
        }

        $file_data = file_get_contents($file);
        if (!$file_data) {
           throw new Exception('Could not read the file data.');
        }
        $cities = json_decode($file_data, true);
        $resuls = [];
        foreach ($cities as $city) {
            $resuls[]['city'] = $city['ville'];
        }
        return $resuls;
    }
}