<?php

namespace App\Entity;

use App\Repository\EstablishmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstablishmentRepository::class)]
class Establishment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "establishmenttype")]
    private ?string $type = null;

    #[ORM\Column(nullable: true, length: 255)]
    private ?string $target = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'establishments')]
    private Collection $handler;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $custom_type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $custom_address = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $custom_phone = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $custom_city = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->handler = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): static
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getHandler(): Collection
    {
        return $this->handler;
    }

    public function addHandler(User $handler): static
    {
        if (!$this->handler->contains($handler)) {
            $this->handler->add($handler);
        }

        return $this;
    }

    public function removeHandler(User $handler): static
    {
        $this->handler->removeElement($handler);

        return $this;
    }

    public function getCustomType(): ?string
    {
        return $this->custom_type;
    }

    public function setCustomType(?string $custom_type): static
    {
        $this->custom_type = $custom_type;

        return $this;
    }

    public function getCustomAddress(): ?string
    {
        return $this->custom_address;
    }

    public function setCustomAddress(?string $custom_address): static
    {
        $this->custom_address = $custom_address;

        return $this;
    }

    public function getCustomPhone(): ?string
    {
        return $this->custom_phone;
    }

    public function setCustomPhone(?string $custom_phone): static
    {
        $this->custom_phone = $custom_phone;

        return $this;
    }

    public function getCustomCity(): ?string
    {
        return $this->custom_city;
    }

    public function setCustomCity(?string $custom_city): static
    {
        $this->custom_city = $custom_city;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
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
}
