<?php

namespace App\Entity;

use App\Repository\MobileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MobileRepository::class)]
class Mobile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imei = null;

    #[ORM\Column(length: 255, name: "ext_corta")]
    private ?string $extCorta = null;

    #[ORM\Column(length: 255, name: "ext_larga")]
    private ?string $extLarga = null;

    #[ORM\ManyToOne(inversedBy: 'mobiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PhoneModel $phoneModel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImei(): ?string
    {
        return $this->imei;
    }

    public function setImei(string $imei): static
    {
        $this->imei = $imei;

        return $this;
    }

    public function getExtCorta(): ?string
    {
        return $this->extCorta;
    }

    public function setExtCorta(string $extCorta): static
    {
        $this->extCorta = $extCorta;

        return $this;
    }

    public function getExtLarga(): ?string
    {
        return $this->extLarga;
    }

    public function setExtLarga(string $extLarga): static
    {
        $this->extLarga = $extLarga;

        return $this;
    }

    public function getPhoneModel(): ?PhoneModel
    {
        return $this->phoneModel;
    }

    public function setPhoneModel(?PhoneModel $phoneModel): static
    {
        $this->phoneModel = $phoneModel;

        return $this;
    }
}
