<?php

namespace App\Entity;

use App\Repository\BatteryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BatteryRepository::class)]
class Battery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ref = null;

    #[ORM\Column(length: 255, name: "serial_no")]
    private ?string $serialNo = null;

    #[ORM\ManyToOne(inversedBy: 'batteries')]
    private ?Talkie $talkie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getSerialNo(): ?string
    {
        return $this->serialNo;
    }

    public function setSerialNo(string $serialNo): static
    {
        $this->serialNo = $serialNo;

        return $this;
    }

    public function getTalkie(): ?Talkie
    {
        return $this->talkie;
    }

    public function setTalkie(?Talkie $talkie): static
    {
        $this->talkie = $talkie;

        return $this;
    }
}
