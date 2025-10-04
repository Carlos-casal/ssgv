<?php

namespace App\Entity;

use App\Repository\VolunteerServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types; // Necesario para Types::DATETIME_MUTABLE y Types::FLOAT, Types::TEXT

/**
 * Represents the association between a Volunteer and a Service they are participating in.
 * This entity acts as a link table and holds the collection of clock-in/out records for that participation.
 */
#[ORM\Entity(repositoryClass: VolunteerServiceRepository::class)]
class VolunteerService
{
    /**
     * @var int|null The unique identifier for this association.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Volunteer|null The volunteer participating in the service.
     */
    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Volunteer $volunteer = null;

    /**
     * @var Service|null The service the volunteer is participating in.
     */
    #[ORM\ManyToOne(inversedBy: 'volunteerServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    /**
     * @var Collection<int, Fichaje> A collection of clock-in/out records for this specific volunteer-service participation.
     */
    #[ORM\OneToMany(mappedBy: 'volunteerService', targetEntity: Fichaje::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['startTime' => 'ASC'])]
    private Collection $fichajes;

    /**
     * Initializes the fichajes collection.
     */
    public function __construct()
    {
        $this->fichajes = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for this association.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the associated volunteer.
     * @return Volunteer|null
     */
    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    /**
     * Sets the associated volunteer.
     * @param Volunteer|null $volunteer The volunteer.
     * @return static
     */
    public function setVolunteer(?Volunteer $volunteer): static
    {
        $this->volunteer = $volunteer;

        return $this;
    }

    /**
     * Gets the associated service.
     * @return Service|null
     */
    public function getService(): ?Service
    {
        return $this->service;
    }

    /**
     * Sets the associated service.
     * @param Service|null $service The service.
     * @return static
     */
    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Gets the collection of clock-in/out records (fichajes).
     * @return Collection<int, Fichaje>
     */
    public function getFichajes(): Collection
    {
        return $this->fichajes;
    }

    /**
     * Adds a clock-in/out record to this association.
     * @param Fichaje $fichaje The fichaje to add.
     * @return static
     */
    public function addFichaje(Fichaje $fichaje): static
    {
        if (!$this->fichajes->contains($fichaje)) {
            $this->fichajes->add($fichaje);
            $fichaje->setVolunteerService($this);
        }

        return $this;
    }

    /**
     * Removes a clock-in/out record from this association.
     * @param Fichaje $fichaje The fichaje to remove.
     * @return static
     */
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

    /**
     * Finds and returns the currently open clock-in record (one without an end time).
     * @return Fichaje|null The open fichaje, or null if none is open.
     */
    public function getOpenFichaje(): ?Fichaje
    {
        foreach ($this->fichajes as $fichaje) {
            if ($fichaje->getEndTime() === null) {
                return $fichaje;
            }
        }

        return null;
    }

    /**
     * Calculates the total duration of the volunteer's participation in minutes.
     * It sums the duration of all associated clock-in/out records. For open records (without an end time),
     * it uses the service's end time as the calculation basis.
     *
     * @return int The total duration in minutes.
     */
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