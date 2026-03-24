<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Api\RegisterController;
use App\Controller\Api\SocialAuthController;
use App\Dto\RegisterUser;
use App\Dto\SocialProfile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
#[ApiResource(
    security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_MANAGER')",
    operations: [
        new Post(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/auth/social',
            controller: SocialAuthController::class,
            denormalizationContext: ['groups' => ['create']],
            input: SocialProfile::class
        ),
        new Post(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/auth/register',
            input: RegisterUser::class,
            denormalizationContext: ['groups' => ['signup']],
            controller: RegisterController::class,
        )
    ]
)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const string ROLE_BUSINESS = 'ROLE_BUSINESS';
    public const string ROLE_PHARMACY_ADMIN = 'ROLE_PHARMACY_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "json")]
    private array $roles = [User::ROLE_USER];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = true;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    /**
     * @var Collection<int, Establishment>
     */
    #[ORM\ManyToMany(targetEntity: Establishment::class, mappedBy: 'handler')]
    private Collection $establishments;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $google = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $username = null;

    public function __construct()
    {
        $this->establishments = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getUserIdentifier(): string { return $this->email; }

    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_MOBILE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function eraseCredentials(): void {}

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $name): self { $this->firstName = $name; return $this; }

    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $name): self { $this->lastName = $name; return $this; }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Establishment>
     */
    public function getEstablishments(): Collection
    {
        return $this->establishments;
    }

    public function addEstablishment(Establishment $establishment): static
    {
        if (!$this->establishments->contains($establishment)) {
            $this->establishments->add($establishment);
            $establishment->addHandler($this);
        }

        return $this;
    }

    public function removeEstablishment(Establishment $establishment): static
    {
        if ($this->establishments->removeElement($establishment)) {
            $establishment->removeHandler($this);
        }

        return $this;
    }

    public function getGoogle(): ?string
    {
        return $this->google;
    }

    public function setGoogle(?string $google): static
    {
        $this->google = $google;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
