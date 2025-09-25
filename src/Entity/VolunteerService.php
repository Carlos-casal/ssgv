<?php

namespace App\Entity;

use App\Repository\VolunteerServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types; // Necesario para Types::DATETIME_MUTABLE y Types::FLOAT, Types::TEXT

#[ORM\Entity(repositoryClass: VolunteerServiceRepository::class)]
class VolunteerService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    // Si respondiste 'yes' a la pregunta de nullable, aquí diría 'nullable: true'.
    // Si quieres que no sea nulo, déjalo como 'nullable: false' o elimínalo (por defecto es false).
    // Te recomiendo que sea 'nullable: false' para consistencia.
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\OneToMany(mappedBy: 'volunteerService', targetEntity: Fichaje::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['startTime' => 'ASC'])]
    private Collection $fichajes;

    public function __construct()
    {
        $this->fichajes = new ArrayCollection();
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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, Fichaje>
     */
    public function getFichajes(): Collection
    {
        return $this->fichajes;
    }

    public function addFichaje(Fichaje $fichaje): static
    {
        if (!$this->fichajes->contains($fichaje)) {
            $this->fichajes->add($fichaje);
            $fichaje->setVolunteerService($this);
        }

        return $this;
    }

    public function removeFichaje(Fichaje $fichaje): static
    {
        if ($this->fichajes->removeElement($fichaje)) {
            // set the owning side to null (unless already changed)
            if ($fichaje->getVolunteerService() === $this) {
                $fichaje->setVolunteerService(null);
            }
        }

        return $this;
    }

    public function getOpenFichaje(): ?Fichaje
    {
        foreach ($this->fichajes as $fichaje) {
            if ($fichaje->getEndTime() === null) {
                return $fichaje;
            }
        }

        return null;
    }

    public function calculateTotalDuration(): int
    {
        if ($this->fichajes->isEmpty()) {
            return 0;
        }

        $totalDuration = 0;
        $serviceEndTime = $this->getService()->getEndDate();

        foreach ($this->fichajes as $fichaje) {
            $startTime = $fichaje->getStartTime();
            $endTime = $fichaje->getEndTime();

            if ($startTime === null) {
                continue;
            }

            $effectiveEndTime = $endTime;
            if ($effectiveEndTime === null) {
                $effectiveEndTime = $serviceEndTime;
            }

            if ($effectiveEndTime < $startTime) {
                continue;
            }

            $durationInSeconds = $effectiveEndTime->getTimestamp() - $startTime->getTimestamp();
            $totalDuration += $durationInSeconds;
        }

        return round($totalDuration / 60);
    }
}