<?php

namespace App\Entity;

use App\Repository\OpenPharmacyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpenPharmacyRepository::class)]
class OpenPharmacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $town = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $dutyType = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $gardeStatus = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'openingHours')]
    private ?Pharmacy $pharmacy = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $amFrom = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $pmTo = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $amTo = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $pmFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $day = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $source = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDutyType(): ?string
    {
        return $this->dutyType;
    }

    public function setDutyType(?string $dutyType): static
    {
        $this->dutyType = $dutyType;

        return $this;
    }

    public function getGardeStatus(): ?string
    {
        return $this->gardeStatus;
    }

    public function setGardeStatus(?string $gardeStatus): static
    {
        $this->gardeStatus = $gardeStatus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPharmacy(): ?Pharmacy
    {
        return $this->pharmacy;
    }

    public function setPharmacy(?Pharmacy $pharmacy): static
    {
        $this->pharmacy = $pharmacy;

        return $this;
    }

    public function getAmFrom(): ?\DateTime
    {
        return $this->amFrom;
    }

    public function setAmFrom(\DateTime $amFrom): static
    {
        $this->amFrom = $amFrom;

        return $this;
    }

    public function getPmTo(): ?\DateTime
    {
        return $this->pmTo;
    }

    public function setPmTo(\DateTime $pmTo): static
    {
        $this->pmTo = $pmTo;

        return $this;
    }

    public function getAmTo(): ?\DateTime
    {
        return $this->amTo;
    }

    public function setAmTo(?\DateTime $amTo): static
    {
        $this->amTo = $amTo;

        return $this;
    }

    public function getPmFrom(): ?\DateTime
    {
        return $this->pmFrom;
    }

    public function setPmFrom(?\DateTime $pmFrom): static
    {
        $this->pmFrom = $pmFrom;

        return $this;
    }

    public function getDay(): ?\DateTime
    {
        return $this->day;
    }

    public function setDay(\DateTime $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }
}
