<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SpeciesRepository::class)
 */
class Species
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"search"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=AssoSpecies::class, mappedBy="species")
     */
    private $assoSpecies;

    /**
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="species")
     */
    private $animals;

    public function __construct()
    {
        $this->assoSpecies = new ArrayCollection();
        $this->animals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, AssoSpecies>
     */
    public function getAssoSpecies(): Collection
    {
        return $this->assoSpecies;
    }

    public function addAssoSpecies(AssoSpecies $assoSpecies): self
    {
        if (!$this->assoSpecies->contains($assoSpecies)) {
            $this->assoSpecies[] = $assoSpecies;
            $assoSpecies->setSpecies($this);
        }

        return $this;
    }

    public function removeAssoSpecies(AssoSpecies $assoSpecies): self
    {
        if ($this->assoSpecies->removeElement($assoSpecies)) {
            // set the owning side to null (unless already changed)
            if ($assoSpecies->getSpecies() === $this) {
                $assoSpecies->setSpecies(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setSpecies($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getSpecies() === $this) {
                $animal->setSpecies(null);
            }
        }

        return $this;
    }
}
