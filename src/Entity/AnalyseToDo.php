<?php

namespace App\Entity;

use App\Repository\AnalyseToDoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalyseToDoRepository::class)]
class AnalyseToDo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'analyseToDos')]
    private ?Analyses $analyse = null;

    #[ORM\ManyToOne(inversedBy: 'analyseToDos')]
    private ?Consultation $consultation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasResult = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $remarks = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $result = null;

    #[ORM\ManyToOne]
    private ?Laboratory $laboratory = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnalyse(): ?Analyses
    {
        return $this->analyse;
    }

    public function setAnalyse(?Analyses $analyse): static
    {
        $this->analyse = $analyse;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): static
    {
        $this->consultation = $consultation;

        return $this;
    }

    public function hasResult(): ?bool
    {
        return $this->hasResult;
    }

    public function setHasResult(?bool $hasResult): static
    {
        $this->hasResult = $hasResult;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(string $remarks): static
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getLaboratory(): ?Laboratory
    {
        return $this->laboratory;
    }

    public function setLaboratory(?Laboratory $laboratory): static
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
