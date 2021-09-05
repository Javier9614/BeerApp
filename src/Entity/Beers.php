<?php

namespace App\Entity;

use App\Repository\BeersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeersRepository::class)
 */
class Beers
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
     * @ORM\Column(type="float")
     */
    private $graduation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $style;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fermentationType;

    /**
     * @ORM\Column(type="integer")
     */
    private $introduced;

    /**
     * @ORM\ManyToOne(targetEntity=Countries::class, inversedBy="beers")
     */
    private $country;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favs")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getGraduation(): ?float
    {
        return $this->graduation;
    }

    public function setGraduation(float $graduation): self
    {
        $this->graduation = $graduation;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getFermentationType(): ?string
    {
        return $this->fermentationType;
    }

    public function setFermentationType(string $fermentationType): self
    {
        $this->fermentationType = $fermentationType;

        return $this;
    }

    public function getIntroduced(): ?int
    {
        return $this->introduced;
    }

    public function setIntroduced(int $introduced): self
    {
        $this->introduced = $introduced;

        return $this;
    }

    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFav($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFav($this);
        }

        return $this;
    }
}
