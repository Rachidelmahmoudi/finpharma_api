<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Repository\MedicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MedicationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/medications',
            normalizationContext: ['groups' => ['read']],
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Medication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Groups(['read'])]
    private ?string $presentation = null;

     #[Groups(['read'])]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $dosage = null;

     #[Groups(['read'])]
    #[ORM\Column(length: 255)]
    private ?string $composition = null;

     #[Groups(['read'])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups(['read'])]
    #[ORM\Column]
    private ?float $hospitalPrice = null;

    #[Groups(['read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $indications = null;

    #[Groups(['read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contraindications = null;

    #[Groups(['read'])]
    #[ORM\Column(length: 1)]
    private ?string $status = null;

     #[Groups(['read'])]
    #[ORM\Column(length: 1)]
    private ?string $nature = null;

    /**
     * @var Collection<int, Prescription>
     */
    #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'medication')]
    private Collection $prescriptions;

    #[ORM\Column]
    private ?bool $isRefundable = null;

    #[ORM\Column]
    private ?bool $isRecalled = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $manufacturer = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $therapeuticClass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $canPregnancy = null;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function setComposition(string $composition): static
    {
        $this->composition = $composition;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getHospitalPrice(): ?float
    {
        return $this->hospitalPrice;
    }

    public function setHospitalPrice(float $hospitalPrice): static
    {
        $this->hospitalPrice = $hospitalPrice;

        return $this;
    }

    public function getIndications(): ?string
    {
        return $this->indications;
    }

    public function setIndications(?string $indications): static
    {
        $this->indications = $indications;

        return $this;
    }

    public function getContraindications(): ?string
    {
        return $this->contraindications;
    }

    public function setContraindications(?string $contraindications): static
    {
        $this->contraindications = $contraindications;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): static
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * @return Collection<int, Prescription>
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescription $prescription): static
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions->add($prescription);
            $prescription->setMedication($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getMedication() === $this) {
                $prescription->setMedication(null);
            }
        }

        return $this;
    }

    public function isRefundable(): ?bool
    {
        return $this->isRefundable;
    }

    public function setIsRefundable(bool $isRefundable): static
    {
        $this->isRefundable = $isRefundable;

        return $this;
    }

    public function isRecalled(): ?bool
    {
        return $this->isRecalled;
    }

    public function setIsRecalled(bool $isRecalled): static
    {
        $this->isRecalled = $isRecalled;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): static
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getTherapeuticClass(): ?string
    {
        return $this->therapeuticClass;
    }

    public function setTherapeuticClass(?string $therapeuticClass): static
    {
        $this->therapeuticClass = $therapeuticClass;

        return $this;
    }

    public function isCanPregnancy(): ?bool
    {
        return $this->canPregnancy;
    }

    public function setCanPregnancy(?bool $canPregnancy): static
    {
        $this->canPregnancy = $canPregnancy;

        return $this;
    }
}
