<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=AssoSpecies::class, mappedBy="species")
     */
    private $assoSpecies;

    public function __construct()
    {
        $this->assoSpecies = new ArrayCollection();
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
}
