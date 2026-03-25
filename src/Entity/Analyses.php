<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\AnalysesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnalysesRepository::class)]
#[ApiResource(
    paginationEnabled: false,
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_PUBLIC_API')",
            uriTemplate: '/public/m/analyses',
            normalizationContext: ['groups' => ['read']],
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
class Analyses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'analyses')]
    private ?AnalyseCategory $category = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $bIndex = null;

    /**
     * @var Collection<int, AnalyseToDo>
     */
    #[ORM\OneToMany(targetEntity: AnalyseToDo::class, mappedBy: 'analyse')]
    private Collection $analyseToDos;

    public function __construct()
    {
        $this->analyseToDos = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?AnalyseCategory
    {
        return $this->category;
    }

    public function setCategory(?AnalyseCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBIndex(): ?string
    {
        return $this->bIndex;
    }

    public function setBIndex(?string $bIndex): static
    {
        $this->bIndex = $bIndex;

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
            $analyseToDo->setAnalyse($this);
        }

        return $this;
    }

    public function removeAnalyseToDo(AnalyseToDo $analyseToDo): static
    {
        if ($this->analyseToDos->removeElement($analyseToDo)) {
            // set the owning side to null (unless already changed)
            if ($analyseToDo->getAnalyse() === $this) {
                $analyseToDo->setAnalyse(null);
            }
        }

        return $this;
    }
}
