<?php

namespace App\Util;


interface Searchable {
    public function search(string $query): mixed;
}