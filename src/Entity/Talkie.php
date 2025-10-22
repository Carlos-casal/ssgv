<?php

namespace App\Entity;

use App\Repository\TalkieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TalkieRepository::class)]
class Talkie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255, name: "serial_no")]
    private ?string $serialNo = null;

    #[ORM\Column(length: 255, name: "hw_version")]
    private ?string $hwVersion = null;

    #[ORM\Column(length: 255)]
    private ?string $modelo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tei = null;

    #[ORM\OneToMany(mappedBy: 'talkie', targetEntity: Battery::class)]
    private Collection $batteries;

    #[ORM\OneToMany(mappedBy: 'talkie', targetEntity: Ptt::class)]
    private Collection $ptts;

    public function __construct()
    {
        $this->batteries = new ArrayCollection();
        $this->ptts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

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

    public function getHwVersion(): ?string
    {
        return $this->hwVersion;
    }

    public function setHwVersion(string $hwVersion): static
    {
        $this->hwVersion = $hwVersion;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getTei(): ?string
    {
        return $this->tei;
    }

    public function setTei(?string $tei): static
    {
        $this->tei = $tei;

        return $this;
    }

    /**
     * @return Collection<int, Battery>
     */
    public function getBatteries(): Collection
    {
        return $this->batteries;
    }

    public function addBattery(Battery $battery): static
    {
        if (!$this->batteries->contains($battery)) {
            $this->batteries->add($battery);
            $battery->setTalkie($this);
        }

        return $this;
    }

    public function removeBattery(Battery $battery): static
    {
        if ($this->batteries->removeElement($battery)) {
            // set the owning side to null (unless already changed)
            if ($battery->getTalkie() === $this) {
                $battery->setTalkie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ptt>
     */
    public function getPtts(): Collection
    {
        return $this->ptts;
    }

    public function addPtt(Ptt $ptt): static
    {
        if (!$this->ptts->contains($ptt)) {
            $this->ptts->add($ptt);
            $ptt->setTalkie($this);
        }

        return $this;
    }

    public function removePtt(Ptt $ptt): static
    {
        if ($this->ptts->removeElement($ptt)) {
            // set the owning side to null (unless already changed)
            if ($ptt->getTalkie() === $this) {
                $ptt->setTalkie(null);
            }
        }

        return $this;
    }
}
