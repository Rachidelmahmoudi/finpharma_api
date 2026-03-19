<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?Doctor $doctor = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?MedicalFile $medicalFile = null;

    /**
     * @var Collection<int, Prescription>
     */
    #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'consultation')]
    private Collection $prescriptions;

    /**
     * @var Collection<int, AnalyseToDo>
     */
    #[ORM\OneToMany(targetEntity: AnalyseToDo::class, mappedBy: 'consultation')]
    private Collection $analyseToDos;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $type = null;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
        $this->analyseToDos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

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

    public function getMedicalFile(): ?MedicalFile
    {
        return $this->medicalFile;
    }

    public function setMedicalFile(?MedicalFile $medicalFile): static
    {
        $this->medicalFile = $medicalFile;

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
            $prescription->setConsultation($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getConsultation() === $this) {
                $prescription->setConsultation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnalyseToDo>
     */
    public function getAnalyseToDos(): Collection
    {
        return $this->analyseToDos;
    }

    public function addAnalyseToDo(AnalyseToDo $analyseToDo): static
    {
        if (!$this->analyseToDos->contains($analyseToDo)) {
            $this->analyseToDos->add($analyseToDo);
            $analyseToDo->setConsultation($this);
        }

        return $this;
    }

    public function removeAnalyseToDo(AnalyseToDo $analyseToDo): static
    {
        if ($this->analyseToDos->removeElement($analyseToDo)) {
            // set the owning side to null (unless already changed)
            if ($analyseToDo->getConsultation() === $this) {
                $analyseToDo->setConsultation(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
