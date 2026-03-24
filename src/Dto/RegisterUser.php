<?php

namespace App\Dto;


use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[Map(target: User::class)]
final class RegisterUser
{
    #[Groups(['signup'])]
    #[Assert\NotBlank]
    public string $phone;

    #[Groups(['signup'])]
    #[Assert\NotBlank]
    public string $password;

    #[Groups(['signup'])]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Adresse email invalide.'
    )]
    public ?string $email = null;
}