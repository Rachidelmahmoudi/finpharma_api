<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PharmacyRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Api\MobilePharmacyController;
use App\State\PharmacyStateProvider;
use App\Dto\CreatePharmacy;
use App\State\AddPharmacyProcessor;
use App\State\MyPharmacyProvider;

#[ORM\Entity(repositoryClass: PharmacyRepository::class)]
#[ORM\Table(name: "pharmacies")]
#[ApiResource(
    security: "is_granted('ROLE_MANAGER') or is_granted('ROLE_SUPER_ADMIN')",
    operations: [
        new GetCollection(
            provider: PharmacyStateProvider::class,
            normalizationContext: ['groups' => ['read']],
        ),
        new GetCollection(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/pharmacies',
            normalizationContext: ['groups' => ['read']],
            provider: PharmacyStateProvider::class,
        ),
        new GetCollection(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/pharmacies',
            normalizationContext: ['groups' => ['read']],
            provider: PharmacyStateProvider::class,
        ),
        new Get(
            security: "is_granted('ROLE_PHARMACY_ADMIN')",
            uriTemplate: '/my-pharmacy',
            provider: MyPharmacyProvider::class,
        ),
        new Put(
            security: "is_granted('ROLE_PHARMACY_ADMIN')",
            uriTemplate: '/my-pharmacy',
            processor: AddPharmacyProcessor::class,
            normalizationContext: ['groups' => ['create']],
            input: CreatePharmacy::class
        ),
        new Post(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/add-pharmacy',
            processor: AddPharmacyProcessor::class,
            denormalizationContext: ['groups' => ['create']],
            input: CreatePharmacy::class
        )
    ]
)]

class Pharmacy
{
    #[Groups(['read'])]
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('App\\Doctrine\\IdGenerator')]
    private ?string $id = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 150)]
    private string $name;

    #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private string $address;

    #[Groups(['read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    #[Groups(['read'])]
    #[ORM\Column(type: "float", nullable: true)]
    private ?float $latitude;

    #[Groups(['read'])]
    #[ORM\Column(type: "float", nullable: true)]
    private ?float $longitude;

    #[Groups(['read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleMapsUrl = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $town = null;

    /**
     * @var Collection<int, OpenPharmacy>
     */
    #[ORM\OneToMany(targetEntity: OpenPharmacy::class, mappedBy: 'pharmacy')]
    private Collection $openingHours;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAlwaysOpen = null;

    public function __construct()
    {
        $this->openingHours = new ArrayCollection();
    }

    public function getId(): ?string { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getAddress(): string { return $this->address; }
    public function setAddress(string $address): self { $this->address = $address; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(?float $lat): self { $this->latitude = $lat; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(?float $lng): self { $this->longitude = $lng; return $this; }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getGoogleMapsUrl(): ?string
    {
        return $this->googleMapsUrl;
    }

    public function setGoogleMapsUrl(?string $googleMapsUrl): static
    {
        $this->googleMapsUrl = $googleMapsUrl;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): static
    {
        $this->town = $town;

        return $this;
    }

    /**
     * @return Collection<int, OpenPharmacy>
     */
    public function getOpeningHours(): Collection
    {
        return $this->openingHours;
    }

    public function addOpeningHour(OpenPharmacy $openingHour): static
    {
        if (!$this->openingHours->contains($openingHour)) {
            $this->openingHours->add($openingHour);
            $openingHour->setPharmacy($this);
        }

        return $this;
    }

    public function removeOpeningHour(OpenPharmacy $openingHour): static
    {
        if ($this->openingHours->removeElement($openingHour)) {
            // set the owning side to null (unless already changed)
            if ($openingHour->getPharmacy() === $this) {
                $openingHour->setPharmacy(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isAlwaysOpen(): ?bool
    {
        return $this->isAlwaysOpen;
    }

    public function setIsAlwaysOpen(?bool $isAlwaysOpen): static
    {
        $this->isAlwaysOpen = $isAlwaysOpen;

        return $this;
    }
}
