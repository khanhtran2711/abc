<?php

namespace App\Entity;

use App\Repository\CatAniRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CatAniRepository::class)
 */
class CatAni
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
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="cat")
     */
    private $ani;

    public function __construct()
    {
        $this->ani = new ArrayCollection();
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
     * @return Collection<int, Animal>
     */
    public function getAni(): Collection
    {
        return $this->ani;
    }

    public function addAni(Animal $ani): self
    {
        if (!$this->ani->contains($ani)) {
            $this->ani[] = $ani;
            $ani->setCat($this);
        }

        return $this;
    }

    public function removeAni(Animal $ani): self
    {
        if ($this->ani->removeElement($ani)) {
            // set the owning side to null (unless already changed)
            if ($ani->getCat() === $this) {
                $ani->setCat(null);
            }
        }

        return $this;
    }
}
