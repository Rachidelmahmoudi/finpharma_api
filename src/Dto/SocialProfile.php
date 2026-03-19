<?php

namespace App\Dto;


use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[Map(target: User::class)]
final class SocialProfile
{
    #[Assert\NotBlank]
    #[Groups(['create'])]
    public string $name;

    #[Groups(['create'])]
    public string $familyName;

    #[Groups(['create'])]
    public string $givenName;

    #[Assert\NotBlank]
    #[Groups(['create'])]
    public string $id;

    #[Groups(['create'])]
    public string $imageUrl;

    #[Groups(['create'])]
    #[Assert\Email(
        message: 'Adresse email invalide.'
    )]
    public ?string $email = null;
}