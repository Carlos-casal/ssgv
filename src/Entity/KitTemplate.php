<?php

namespace App\Entity;

use App\Repository\KitTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitTemplateRepository::class)]
class KitTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'template', targetEntity: KitTemplateItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, KitTemplateItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(KitTemplateItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setTemplate($this);
        }
        return $this;
    }

    public function removeItem(KitTemplateItem $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getTemplate() === $this) {
                $item->setTemplate(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
