<?php

namespace App\Entity;

use App\Repository\PhoneModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhoneModelRepository::class)]
class PhoneModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $make = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\OneToMany(mappedBy: 'phoneModel', targetEntity: Mobile::class)]
    private Collection $mobiles;

    public function __construct()
    {
        $this->mobiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): static
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection<int, Mobile>
     */
    public function getMobiles(): Collection
    {
        return $this->mobiles;
    }

    public function addMobile(Mobile $mobile): static
    {
        if (!$this->mobiles->contains($mobile)) {
            $this->mobiles->add($mobile);
            $mobile->setPhoneModel($this);
        }

        return $this;
    }

    public function removeMobile(Mobile $mobile): static
    {
        if ($this->mobiles->removeElement($mobile)) {
            // set the owning side to null (unless already changed)
            if ($mobile->getPhoneModel() === $this) {
                $mobile->setPhoneModel(null);
            }
        }

        return $this;
    }
}
