<?php

namespace App\Entity;

use App\Repository\KitTemplateItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitTemplateItemRepository::class)]
class KitTemplateItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?KitTemplate $template = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Material $material = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $suggestedName = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemplate(): ?KitTemplate
    {
        return $this->template;
    }

    public function setTemplate(?KitTemplate $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;
        return $this;
    }

    public function getSuggestedName(): ?string
    {
        return $this->suggestedName;
    }

    public function setSuggestedName(?string $suggestedName): static
    {
        $this->suggestedName = $suggestedName;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }
}
