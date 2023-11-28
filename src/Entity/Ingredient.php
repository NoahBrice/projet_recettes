<?php

namespace App\Entity;

use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Odm\Filter\RangeFilter;

#[ApiResource(
    normalizationContext: [
        'groups' => ['ingredient:read'],
    ],
    denormalizationContext: [
        'groups' => ['ingredient:write']
    ]
)]
// #[ApiFilter(RangeFilter::class, properties: ['prix'])]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        min: 2,
        max: 25,
        minMessage: 'Le nom doit faire au moins {{limit}} caractères',
        maxMessage: 'Le nom ne peut pas faire plus de {{limit}} caractères',
    )]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Groups(['ingredient:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 0,
        max: 200,
        notInRangeMessage: 'You must be between {{ min }} euros and {{ max }} euros tall to enter',
    )]
    #[Groups(['ingredient:write', 'ingredient:read'])]
    #[ApiFilter(RangeFilter::class, properties: ['prix'])]
    private ?float $prix = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Recette::class, mappedBy: 'ingredient')]
    private Collection $recettes;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
        $this->recettes = new ArrayCollection();
    }

    public function __tostring()
    {
        return $this->nom;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

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


    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addIngredient($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeIngredient($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
