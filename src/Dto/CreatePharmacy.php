<?php

namespace App\Dto;

use App\Entity\Pharmacy;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[Map(target: Pharmacy::class)]
final class CreatePharmacy
{
    #[Assert\NotBlank]
    #[Groups(['create'])]
    public string $name;

    #[Assert\NotBlank]
    #[Groups(['create'])]
    public string $city;

    // #[Assert\NotBlank]
    #[Groups(['create'])]
    public string $address = '';

    #[Groups(['create'])]
    #[Assert\NotBlank, Assert\Regex(pattern: '/^(?:\+212|0)(5|6|7)\d{8}$/')]
    public ?string $phone = null;

    #[Groups(['create'])]
    #[Assert\Email(
        message: 'Adresse email invalide.'
    )]
    public ?string $email = null;

    #[Groups(['create'])]
    public ?string $town = null;

    #[Groups(['create'])]
    public ?string $customTown = null;

    #[Groups(['create'])]
    #[Assert\NotBlank]
    public ?string $longitude = null;

    #[Groups(['create'])]
    #[Assert\NotBlank]
    public ?string $latitude = null;

    #[Groups(['create'])]
    public ?array $gardeDays = [];

    #[Groups(['create'])]
    public ?bool $isAlwaysOpen = false;

    #[Groups(['create'])]
    public ?string $lang = 'fr';
}