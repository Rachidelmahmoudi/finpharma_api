<?php

namespace App\Form;

enum SearchTypes : string {
    case PHARAMACIES = 'pharmacies';
    case MEDECINE = 'medecine';
    case LABORATORIES = 'laboratories';
    case ALL = 'all';

    public static function types(): array
    {
        return [
            'global_search.pharmacies' => 'pharmacies',
            'global_search.medecins' => 'medecine',
            'global_search.laboratories' => 'laboratories',
        ];
    }
}