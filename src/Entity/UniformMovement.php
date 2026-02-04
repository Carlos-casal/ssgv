<?php

namespace App\Entity;

use App\Repository\UniformMovementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniformMovementRepository::class)]
#[ORM\Table(name: 'uniform_movement')]
class UniformMovement
{
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_RETURN = 'return';
    public const TYPE_EXCHANGE = 'exchange';

    public const REASON_NEW_ASSIGNMENT = 'new_assignment';
    public const REASON_SIZE_CHANGE = 'size_change';
    public const REASON_DAMAGE = 'damage';
    public const REASON_RESIGNATION = 'resignation';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'uniformMovements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column(length: 20)]
    private ?string $movementType = null;

    #[ORM\Column(length: 50)]
    private ?string $reason = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $size = null;

    #[ORM\Column]
    private int $quantity = 1;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => false])]
    private bool $returnToStock = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

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

    public function getMovementType(): ?string
    {
        return $this->movementType;
    }

    public function setMovementType(string $movementType): static
    {
        $this->movementType = $movementType;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isReturnToStock(): bool
    {
        return $this->returnToStock;
    }

    public function setReturnToStock(bool $returnToStock): static
    {
        $this->returnToStock = $returnToStock;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
