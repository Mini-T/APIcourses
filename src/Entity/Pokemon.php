<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use \Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'pokemons')]
#[ORM\Entity]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(name: 'number')]
    private ?int $number = null;

    #[ORM\Column(name: 'description')]
    private ?string $description = null;

    #[ORM\Column(name: 'name')]
    private ?string $name = null;

    #[ORM\Column(name: 'height')]
    private ?float $height = null;

    #[ORM\Column(name: 'weight')]
    private ?float $weight = null;

    #[ORM\ManyToMany(targetEntity: Gender::class)]
    private ?Collection $genders = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'weaknesses')]
    private ?Collection $types = null;

    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: 'types')]
    private ?Collection $weaknesses = null;

    #[ORM\OneToOne(inversedBy: 'regression', targetEntity: Pokemon::class)]
    private ?Pokemon $evolution = null;

    #[ORM\OneToOne(mappedBy: 'evolution', targetEntity: Pokemon::class)]
    private ?Pokemon $regression = null;

    #[ORM\Column(name: 'image')]
    private ?string $imageUrl = null;

    public function __construct()
    {
        $this->genders = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->weaknesses = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int|null $number
     */
    public function setNumber(?int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getHeight(): ?float
    {
        return $this->height;
    }

    /**
     * @param float|null $height
     */
    public function setHeight(?float $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     */
    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }
    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(Category $category): self
    {
        if (is_array($category)) {
            $categoryObject = new Category();
            foreach($category as $key => $value) {
                $method = 'set'.ucfirst($key);
                $categoryObject->$method($value);
            }
            $this->category = $categoryObject;
            return $this;
        }
        $this->category = $category;
        return $this;
    }

    /**
     * @return Pokemon
     */
    public function getEvolution(): ?Pokemon
    {
        return $this->evolution;
    }

    /**
     * @param Pokemon $evolution
     */
    public function setEvolution(Pokemon|array|null $evolution): self
    {
        if (is_array($evolution)) {
            $categoryObject = new Pokemon();
            foreach($evolution as $key => $value) {
                $method = 'set'.ucfirst($key);
                $categoryObject->$method($value);
            }
            $this->evolution = $evolution;
            return $this;
        }
        $this->evolution = $evolution;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return Pokemon
     */
    public function getRegression(): ?Pokemon
    {
        return $this->regression;
    }

    /**
     * @param Pokemon $regression
     */
    public function setRegression(Pokemon|array|null $regression): self
    {
        if (is_array($regression)) {
            $categoryObject = new Pokemon();
            foreach($regression as $key => $value) {
                $method = 'set'.ucfirst($key);
                $categoryObject->$method($value);
            }
            $this->regression = $regression;
            return $this;
        }
        $this->regression = $regression;
        return $this;
    }

    /**
     * @return Collection|null
     */
    public function setGenders(Collection $genders): self
    {
        $this->genders = $genders;
        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getGenders(): ?Collection
    {
        return $this->genders;
    }

    /**
     * @param Collection|null $genders
     */
    public function addGender(?Gender $gender): self
    {
        if (!$this->genders->contains($gender)) {
            $this->genders->add($gender);
        }

        return $this;
    }

    public function removeGender(?Gender $gender): self {
        if ($this->genders->contains($gender)) {
            $this->genders->remove($gender);
        }

        return $this;
    }

    public function setTypes(Collection $types): self {
        $this->types = $types;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getTypes(): ?Collection
    {
        return $this->types;
    }

    /**
     * @param Collection|null $types
     */
    public function addType(?Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }

        return $this;
    }

    public function removeType(?Type $type): self {
        if ($this->types->contains($type)) {
            $this->types->remove($type);
        }

        return $this;
    }
    /**
     * @return Collection|null
     */
    public function getWeaknesses(): ?Collection
    {
        return $this->weaknesses;
    }

    public function setWeaknesses(Collection $weaknesses): ?self
    {
        $this->weaknesses = $weaknesses;
        return $this;
    }


    public function addWeakness(?Type $weakness): self
    {
        if (!$this->weaknesses->contains($weakness)) {
            $this->weaknesses->add($weakness);
        }

        return $this;
    }

    public function removeWeakness(?Type $weakness): self {
        if ($this->weaknesses->contains($weakness)) {
            $this->weaknesses->remove($weakness);
        }

        return $this;
    }
}