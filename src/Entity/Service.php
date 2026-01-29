<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeration = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationLimitDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeAtBase = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $departureTime = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxAttendees = null;

    #[ORM\ManyToOne(targetEntity: ServiceSubcategory::class, inversedBy: 'services')]
    private ?ServiceSubcategory $subcategory = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locality = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: AssistanceConfirmation::class, orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $assistanceConfirmations;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: VolunteerService::class, orphanRemoval: true)]
    private Collection $volunteerServices;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ServiceMaterial::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $serviceMaterials;

    #[ORM\ManyToMany(targetEntity: Vehicle::class)]
    private Collection $vehicles;

    #[ORM\Column(nullable: true)]
    private ?int $numTes = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTts = null;

    #[ORM\Column(nullable: true)]
    private ?int $numDue = null;

    #[ORM\Column(nullable: true)]
    private ?int $numDoctors = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tasks = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $whatsappMessage = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isArchived = false;

    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
        $this->volunteerServices = new ArrayCollection();
        $this->serviceMaterials = new ArrayCollection();
        $this->vehicles = new ArrayCollection();
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

    public function getSubcategory(): ?ServiceSubcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?ServiceSubcategory $subcategory): static
    {
        $this->subcategory = $subcategory;
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

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): static
    {
        $this->locality = $locality;
        return $this;
    }

    public function getAssistanceConfirmations(): Collection
    {
        return $this->assistanceConfirmations;
    }

    public function getVolunteerServices(): Collection
    {
        return $this->volunteerServices;
    }

    public function getServiceMaterials(): Collection
    {
        return $this->serviceMaterials;
    }

    public function addServiceMaterial(ServiceMaterial $serviceMaterial): self
    {
        if (!$this->serviceMaterials->contains($serviceMaterial)) {
            $this->serviceMaterials->add($serviceMaterial);
            $serviceMaterial->setService($this);
        }
        return $this;
    }

    public function removeServiceMaterial(ServiceMaterial $serviceMaterial): self
    {
        if ($this->serviceMaterials->removeElement($serviceMaterial)) {
            if ($serviceMaterial->getService() === $this) {
                $serviceMaterial->setService(null);
            }
        }
        return $this;
    }

    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
        }
        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): self
    {
        $this->vehicles->removeElement($vehicle);
        return $this;
    }

    public function getNumTes(): ?int { return $this->numTes; }
    public function setNumTes(?int $numTes): static { $this->numTes = $numTes; return $this; }
    public function getNumTts(): ?int { return $this->numTts; }
    public function setNumTts(?int $numTts): static { $this->numTts = $numTts; return $this; }
    public function getNumDue(): ?int { return $this->numDue; }
    public function setNumDue(?int $numDue): static { $this->numDue = $numDue; return $this; }
    public function getNumDoctors(): ?int { return $this->numDoctors; }
    public function setNumDoctors(?int $numDoctors): static { $this->numDoctors = $numDoctors; return $this; }

    public function getTasks(): ?string { return $this->tasks; }
    public function setTasks(?string $tasks): static { $this->tasks = $tasks; return $this; }

    public function getWhatsappMessage(): ?string { return $this->whatsappMessage; }
    public function setWhatsappMessage(?string $whatsappMessage): static { $this->whatsappMessage = $whatsappMessage; return $this; }

    public function isArchived(): bool { return $this->isArchived; }
    public function setArchived(bool $isArchived): static { $this->isArchived = $isArchived; return $this; }
}
