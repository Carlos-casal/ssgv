<?php

namespace App\Entity;

use App\Repository\ServiceRepository; // Asume que se generará automáticamente
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // Para usar el slug

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')] // Nombre explícito de la tabla
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeration = null; // Numeración

    #[ORM\Column(length: 255)]
    private ?string $title = null; // Título (requerido)

    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null; // Slug para URLs amigables, se generará automáticamente

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null; // Fecha y hora de inicio (requerido)

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null; // Fecha y hora de finalización (requerido)

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationLimitDate = null; // Límite de inscripción

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeAtBase = null; // Hora en base

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $departureTime = null; // Hora de salida

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxAttendees = null; // Máximo asistentes

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null; // Tipo de servicio (ej. evento, formación)

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null; // Categoría del servicio (ej. rescate, medio ambiente)

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null; // Descripción (se usará Textarea para el formulario)

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $recipients = null; // Para los checkboxes de "Enviar a destinatario"

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null; // Fecha de creación

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $collaboration_with_other_services = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requester = null;

      
    // Constructor para inicializar fechas automáticamente si no se establecen
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeration(): ?string
    {
        return $this->numeration;
    }

    public function setNumeration(?string $numeration): static
    {
        $this->numeration = $numeration;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getRegistrationLimitDate(): ?\DateTimeInterface
    {
        return $this->registrationLimitDate;
    }

    public function setRegistrationLimitDate(?\DateTimeInterface $registrationLimitDate): static
    {
        $this->registrationLimitDate = $registrationLimitDate;
        return $this;
    }

    public function getTimeAtBase(): ?\DateTimeInterface
    {
        return $this->timeAtBase;
    }

    public function setTimeAtBase(?\DateTimeInterface $timeAtBase): static
    {
        $this->timeAtBase = $timeAtBase;
        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    public function setDepartureTime(?\DateTimeInterface $departureTime): static
    {
        $this->departureTime = $departureTime;
        return $this;
    }

    public function getMaxAttendees(): ?int
    {
        return $this->maxAttendees;
    }

    public function setMaxAttendees(?int $maxAttendees): static
    {
        $this->maxAttendees = $maxAttendees;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    public function setRecipients(?array $recipients): static
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isCollaborationWithOtherServices(): ?bool
    {
        return $this->collaboration_with_other_services;
    }

    public function setCollaborationWithOtherServices(bool $collaboration_with_other_services): static
    {
        $this->collaboration_with_other_services = $collaboration_with_other_services;

        return $this;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): static
    {
        $this->locality = $locality;

        return $this;
    }

    public function getRequester(): ?string
    {
        return $this->requester;
    }

    public function setRequester(?string $requester): static
    {
        $this->requester = $requester;

        return $this;
    }
}