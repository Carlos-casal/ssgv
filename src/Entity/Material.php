<?php

namespace App\Entity;

use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
#[ORM\Table(name: 'maestro_material')]
class Material
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null; // e.g. 'Sanitario', 'Comunicaciones', 'Logística'

    #[ORM\OneToMany(mappedBy: 'material', targetEntity: ServiceMaterial::class, orphanRemoval: true)]
    private Collection $serviceMaterials;

    public function __construct()
    {
        $this->serviceMaterials = new ArrayCollection();
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, ServiceMaterial>
     */
    public function getServiceMaterials(): Collection
    {
        return $this->serviceMaterials;
    }

    public function addServiceMaterial(ServiceMaterial $serviceMaterial): static
    {
        if (!$this->serviceMaterials->contains($serviceMaterial)) {
            $this->serviceMaterials->add($serviceMaterial);
            $serviceMaterial->setMaterial($this);
        }

        return $this;
    }

    public function removeServiceMaterial(ServiceMaterial $serviceMaterial): static
    {
        if ($this->serviceMaterials->removeElement($serviceMaterial)) {
            if ($serviceMaterial->getMaterial() === $this) {
                $serviceMaterial->setMaterial(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
