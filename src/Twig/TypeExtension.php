<?php
namespace App\Twig;

use Twig\Attribute\AsTwigFilter;

class TypeExtension
{
    #[AsTwigFilter('type')]
    public function getType(mixed $item): string
    {
        $type = substr($item::class, (strripos($item::class, "\\") ?? 0 ) + 1);
        return strtolower($type);
    }
}