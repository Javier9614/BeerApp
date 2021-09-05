<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountriesRepository::class)
 */
class Countries
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Beers::class, mappedBy="country")
     */
    private $beers;

    public function __construct()
    {
        $this->beers = new ArrayCollection();
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
     * @return Collection|Beers[]
     */
    public function getBeers(): Collection
    {
        return $this->beers;
    }

    public function addBeer(Beers $beer): self
    {
        if (!$this->beers->contains($beer)) {
            $this->beers[] = $beer;
            $beer->setCountry($this);
        }

        return $this;
    }

    public function removeBeer(Beers $beer): self
    {
        if ($this->beers->removeElement($beer)) {
            // set the owning side to null (unless already changed)
            if ($beer->getCountry() === $this) {
                $beer->setCountry(null);
            }
        }

        return $this;
    }
}
