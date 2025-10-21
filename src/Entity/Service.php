<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Represents a service or event that volunteers can attend.
 * Contains all details about the service, such as date, time, location, and required resources.
 */
#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')]
class Service
{
    /**
     * @var int|null The unique identifier for the service.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The official numeration or code for the service.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeration = null;

    /**
     * @var string|null The title of the service.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var string|null The URL-friendly slug generated from the title.
     */
    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    /**
     * @var \DateTimeInterface|null The start date and time of the service.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    /**
     * @var \DateTimeInterface|null The end date and time of the service.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    /**
     * @var \DateTimeInterface|null The deadline for volunteers to register for the service.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationLimitDate = null;

    /**
     * @var \DateTimeInterface|null The time volunteers are expected to be at the base.
     */
    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeAtBase = null;

    /**
     * @var \DateTimeInterface|null The departure time from the base.
     */
    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $departureTime = null;

    /**
     * @var int|null The maximum number of volunteers that can attend. Null for no limit.
     */
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxAttendees = null;

    /**
     * @var string|null The type of service (e.g., "Preventivo", "Emergencia").
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    /**
     * @var string|null The category of the service (e.g., "Deportivo", "Cultural").
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null;

    /**
     * @var string|null A detailed description of the service.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var array|null The target audience or recipients of the service.
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $recipients = null;

    /**
     * @var \DateTimeImmutable|null The timestamp when the service was created.
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var \DateTimeImmutable|null The timestamp when the service was last updated.
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var bool Indicates if this service is in collaboration with other entities.
     */
    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $collaborationWithOtherServices = false;

    /**
     * @var string|null The locality or city where the service takes place.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locality = null;

    /**
     * @var string|null The person or entity that requested the service.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requester = null;

    /**
     * @var Collection<int, AssistanceConfirmation> A collection of assistance confirmations for this service.
     */
    #[ORM\OneToMany(mappedBy: 'service', targetEntity: AssistanceConfirmation::class, orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $assistanceConfirmations;

    /**
     * @var Collection<int, VolunteerService> A collection of volunteer-service associations.
     */
    #[ORM\OneToMany(mappedBy: 'service', targetEntity: VolunteerService::class, orphanRemoval: true)]
    private Collection $volunteerServices;

    /**
     * @var string|null The expected affluence or crowd size.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $afluencia = null;

    /**
     * @var int|null The number of Basic Life Support (SVB) units required.
     */
    #[ORM\Column(nullable: true)]
    private ?int $numSvb = null;

    /**
     * @var int|null The number of Advanced Life Support (SVA) units required.
     */
    #[ORM\Column(nullable: true)]
    private ?int $numSva = null;

    /**
     * @var int|null The number of Advanced Nursing Life Support (SVAE) units required.
     */
    #[ORM\Column(nullable: true)]
    private ?int $numSvae = null;

    /**
     * @var int|null The number of doctors required.
     */
    #[ORM\Column(nullable: true)]
    private ?int $numDoctors = null;

    /**
     * @var int|null The number of nurses required.
     */
    #[ORM\Column(nullable: true)]
    private ?int $numNurses = null;

    /**
     * @var bool|null Indicates if a field hospital is required.
     */
    #[ORM\Column(nullable: true)]
    private ?bool $hasFieldHospital = null;

    /**
     * @var string|null A description of the tasks to be performed by volunteers.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tasks = null;

    /**
     * @var bool|null Indicates if provisions (food, water) are provided.
     */
    #[ORM\Column(nullable: true)]
    private ?bool $hasProvisions = null;

    /**
     * Initializes collections.
     */
    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
        $this->volunteerServices = new ArrayCollection();
        $this->requestedVehicles = new ArrayCollection();
        $this->serviceVehicles = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for the service.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the official numeration or code for the service.
     * @return string|null
     */
    public function getNumeration(): ?string
    {
        return $this->numeration;
    }

    /**
     * Sets the official numeration or code for the service.
     * @param string|null $numeration The numeration code.
     * @return static
     */
    public function setNumeration(?string $numeration): static
    {
        $this->numeration = $numeration;
        return $this;
    }

    /**
     * Gets the collection of volunteer-service associations.
     * @return Collection<int, VolunteerService>
     */
    public function getVolunteerServices(): Collection
    {
        return $this->volunteerServices;
    }

    /**
     * Adds a volunteer-service association.
     * @param VolunteerService $volunteerService The association to add.
     * @return static
     */
    public function addVolunteerService(VolunteerService $volunteerService): static
    {
        if (!$this->volunteerServices->contains($volunteerService)) {
            $this->volunteerServices->add($volunteerService);
            $volunteerService->setService($this);
        }

        return $this;
    }

    /**
     * Removes a volunteer-service association.
     * @param VolunteerService $volunteerService The association to remove.
     * @return static
     */
    public function removeVolunteerService(VolunteerService $volunteerService): static
    {
        if ($this->volunteerServices->removeElement($volunteerService)) {
            // set the owning side to null (unless already changed)
            if ($volunteerService->getService() === $this) {
                $volunteerService->setService(null);
            }
        }

        return $this;
    }

    /**
     * Gets the title of the service.
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the service.
     * @param string $title The title.
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets the URL-friendly slug.
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Sets the URL-friendly slug.
     * @param string $slug The slug.
     * @return static
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Gets the start date and time.
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * Sets the start date and time.
     * @param \DateTimeInterface $startDate The start date.
     * @return static
     */
    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Gets the end date and time.
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * Sets the end date and time.
     * @param \DateTimeInterface $endDate The end date.
     * @return static
     */
    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets the registration deadline.
     * @return \DateTimeInterface|null
     */
    public function getRegistrationLimitDate(): ?\DateTimeInterface
    {
        return $this->registrationLimitDate;
    }

    /**
     * Sets the registration deadline.
     * @param \DateTimeInterface|null $registrationLimitDate The deadline.
     * @return static
     */
    public function setRegistrationLimitDate(?\DateTimeInterface $registrationLimitDate): static
    {
        $this->registrationLimitDate = $registrationLimitDate;
        return $this;
    }

    /**
     * Gets the time to be at the base.
     * @return \DateTimeInterface|null
     */
    public function getTimeAtBase(): ?\DateTimeInterface
    {
        return $this->timeAtBase;
    }

    /**
     * Sets the time to be at the base.
     * @param \DateTimeInterface|null $timeAtBase The time.
     * @return static
     */
    public function setTimeAtBase(?\DateTimeInterface $timeAtBase): static
    {
        $this->timeAtBase = $timeAtBase;
        return $this;
    }

    /**
     * Gets the departure time.
     * @return \DateTimeInterface|null
     */
    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departureTime;
    }

    /**
     * Sets the departure time.
     * @param \DateTimeInterface|null $departureTime The time.
     * @return static
     */
    public function setDepartureTime(?\DateTimeInterface $departureTime): static
    {
        $this->departureTime = $departureTime;
        return $this;
    }

    /**
     * Gets the maximum number of attendees.
     * @return int|null
     */
    public function getMaxAttendees(): ?int
    {
        return $this->maxAttendees;
    }

    /**
     * Sets the maximum number of attendees.
     * @param int|null $maxAttendees The maximum number.
     * @return static
     */
    public function setMaxAttendees(?int $maxAttendees): static
    {
        $this->maxAttendees = $maxAttendees;
        return $this;
    }

    /**
     * Gets the type of service.
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of service.
     * @param string|null $type The service type.
     * @return static
     */
    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets the category of the service.
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * Sets the category of the service.
     * @param string|null $category The service category.
     * @return static
     */
    public function setCategory(?string $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Gets the description.
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description.
     * @param string|null $description The description.
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets the target recipients.
     * @return array|null
     */
    public function getRecipients(): ?array
    {
        return $this->recipients;
    }

    /**
     * Sets the target recipients.
     * @param array|null $recipients The recipients.
     * @return static
     */
    public function setRecipients(?array $recipients): static
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * Gets the creation timestamp.
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation timestamp.
     * @param \DateTimeImmutable $createdAt The creation timestamp.
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Gets the last update timestamp.
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Sets the last update timestamp.
     * @param \DateTimeImmutable $updatedAt The update timestamp.
     * @return static
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Checks if the service involves collaboration.
     * @return bool
     */
    public function isCollaborationWithOtherServices(): bool
    {
        return $this->collaborationWithOtherServices;
    }

    /**
     * Sets whether the service involves collaboration.
     * @param bool $collaborationWithOtherServices True if it involves collaboration.
     * @return static
     */
    public function setCollaborationWithOtherServices(bool $collaborationWithOtherServices): static
    {
        $this->collaborationWithOtherServices = $collaborationWithOtherServices;

        return $this;
    }

    /**
     * Gets the locality.
     * @return string|null
     */
    public function getLocality(): ?string
    {
        return $this->locality;
    }

    /**
     * Sets the locality.
     * @param string|null $locality The locality.
     * @return static
     */
    public function setLocality(?string $locality): static
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * Gets the requester.
     * @return string|null
     */
    public function getRequester(): ?string
    {
        return $this->requester;
    }

    /**
     * Sets the requester.
     * @param string|null $requester The requester.
     * @return static
     */
    public function setRequester(?string $requester): static
    {
        $this->requester = $requester;
        return $this;
    }

    /**
     * Gets the collection of assistance confirmations.
     * @return Collection<int, AssistanceConfirmation>
     */
    public function getAssistanceConfirmations(): Collection
    {
        return $this->assistanceConfirmations;
    }

    /**
     * Adds an assistance confirmation.
     * @param AssistanceConfirmation $assistanceConfirmation The confirmation to add.
     * @return static
     */
    public function addAssistanceConfirmation(AssistanceConfirmation $assistanceConfirmation): static
    {
        if (!$this->assistanceConfirmations->contains($assistanceConfirmation)) {
            $this->assistanceConfirmations->add($assistanceConfirmation);
            $assistanceConfirmation->setService($this);
        }

        return $this;
    }

    /**
     * Removes an assistance confirmation.
     * @param AssistanceConfirmation $assistanceConfirmation The confirmation to remove.
     * @return static
     */
    public function removeAssistanceConfirmation(AssistanceConfirmation $assistanceConfirmation): static
    {
        if ($this->assistanceConfirmations->removeElement($assistanceConfirmation)) {
            // set the owning side to null (unless already changed)
            if ($assistanceConfirmation->getService() === $this) {
                $assistanceConfirmation->setService(null);
            }
        }

        return $this;
    }

    /**
     * Gets the expected crowd size.
     * @return string|null
     */
    public function getAfluencia(): ?string
    {
        return $this->afluencia;
    }

    /**
     * Sets the expected crowd size.
     * @param string|null $afluencia The crowd size.
     * @return static
     */
    public function setAfluencia(?string $afluencia): static
    {
        $this->afluencia = $afluencia;

        return $this;
    }

    /**
     * Gets the number of SVB units required.
     * @return int|null
     */
    public function getNumSvb(): ?int
    {
        return $this->numSvb;
    }

    /**
     * Sets the number of SVB units required.
     * @param int|null $numSvb The number of units.
     * @return static
     */
    public function setNumSvb(?int $numSvb): static
    {
        $this->numSvb = $numSvb;

        return $this;
    }

    /**
     * Gets the number of SVA units required.
     * @return int|null
     */
    public function getNumSva(): ?int
    {
        return $this->numSva;
    }

    /**
     * Sets the number of SVA units required.
     * @param int|null $numSva The number of units.
     * @return static
     */
    public function setNumSva(?int $numSva): static
    {
        $this->numSva = $numSva;

        return $this;
    }

    /**
     * Gets the number of SVAE units required.
     * @return int|null
     */
    public function getNumSvae(): ?int
    {
        return $this->numSvae;
    }

    /**
     * Sets the number of SVAE units required.
     * @param int|null $numSvae The number of units.
     * @return static
     */
    public function setNumSvae(?int $numSvae): static
    {
        $this->numSvae = $numSvae;

        return $this;
    }

    /**
     * Gets the number of doctors required.
     * @return int|null
     */
    public function getNumDoctors(): ?int
    {
        return $this->numDoctors;
    }

    /**
     * Sets the number of doctors required.
     * @param int|null $numDoctors The number of doctors.
     * @return static
     */
    public function setNumDoctors(?int $numDoctors): static
    {
        $this->numDoctors = $numDoctors;

        return $this;
    }

    /**
     * Gets the number of nurses required.
     * @return int|null
     */
    public function getNumNurses(): ?int
    {
        return $this->numNurses;
    }

    /**
     * Sets the number of nurses required.
     * @param int|null $numNurses The number of nurses.
     * @return static
     */
    public function setNumNurses(?int $numNurses): static
    {
        $this->numNurses = $numNurses;

        return $this;
    }

    /**
     * Checks if a field hospital is required.
     * @return bool|null
     */
    public function isHasFieldHospital(): ?bool
    {
        return $this->hasFieldHospital;
    }

    /**
     * Sets whether a field hospital is required.
     * @param bool|null $hasFieldHospital True if required.
     * @return static
     */
    public function setHasFieldHospital(?bool $hasFieldHospital): static
    {
        $this->hasFieldHospital = $hasFieldHospital;

        return $this;
    }

    /**
     * Gets the description of tasks.
     * @return string|null
     */
    public function getTasks(): ?string
    {
        return $this->tasks;
    }

    /**
     * Sets the description of tasks.
     * @param string|null $tasks The tasks.
     * @return static
     */
    public function setTasks(?string $tasks): static
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Checks if provisions are provided.
     * @return bool|null
     */
    public function isHasProvisions(): ?bool
    {
        return $this->hasProvisions;
    }

    /**
     * Sets whether provisions are provided.
     * @param bool|null $hasProvisions True if provided.
     * @return static
     */
    public function setHasProvisions(?bool $hasProvisions): static
    {
        $this->hasProvisions = $hasProvisions;

        return $this;
    }

    /**
     * @var string|null The pre-generated WhatsApp message for sharing the service.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $whatsappMessage = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTalkiesDigitales = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTalkiesAnalogicos = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTalkiesBase = null;

    #[ORM\Column(nullable: true)]
    private ?int $numBateriasDigitales = null;

    #[ORM\Column(nullable: true)]
    private ?int $numBateriasAnalogicos = null;

    #[ORM\Column(nullable: true)]
    private ?int $numBateriasBase = null;

    /**
     * @var Collection<int, Vehicle>
     */
    #[ORM\ManyToMany(targetEntity: Vehicle::class, inversedBy: 'services')]
    private Collection $requestedVehicles;

    /**
     * @var Collection<int, ServiceVehicle>
     */
    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ServiceVehicle::class, orphanRemoval: true)]
    private Collection $serviceVehicles;


    /**
     * Gets the WhatsApp message.
     * @return string|null
     */
    public function getWhatsappMessage(): ?string
    {
        return $this->whatsappMessage;
    }

    /**
     * Sets the WhatsApp message.
     * @param string|null $whatsappMessage The message.
     * @return static
     */
    public function setWhatsappMessage(?string $whatsappMessage): static
    {
        $this->whatsappMessage = $whatsappMessage;

        return $this;
    }

    public function getNumTalkiesDigitales(): ?int
    {
        return $this->numTalkiesDigitales;
    }

    public function setNumTalkiesDigitales(?int $numTalkiesDigitales): static
    {
        $this->numTalkiesDigitales = $numTalkiesDigitales;

        return $this;
    }

    public function getNumTalkiesAnalogicos(): ?int
    {
        return $this->numTalkiesAnalogicos;
    }

    public function setNumTalkiesAnalogicos(?int $numTalkiesAnalogicos): static
    {
        $this->numTalkiesAnalogicos = $numTalkiesAnalogicos;

        return $this;
    }

    public function getNumTalkiesBase(): ?int
    {
        return $this->numTalkiesBase;
    }

    public function setNumTalkiesBase(?int $numTalkiesBase): static
    {
        $this->numTalkiesBase = $numTalkiesBase;

        return $this;
    }

    public function getNumBateriasDigitales(): ?int
    {
        return $this->numBateriasDigitales;
    }

    public function setNumBateriasDigitales(?int $numBateriasDigitales): static
    {
        $this->numBateriasDigitales = $numBateriasDigitales;

        return $this;
    }

    public function getNumBateriasAnalogicos(): ?int
    {
        return $this->numBateriasAnalogicos;
    }

    public function setNumBateriasAnalogicos(?int $numBateriasAnalogicos): static
    {
        $this->numBateriasAnalogicos = $numBateriasAnalogicos;

        return $this;
    }

    public function getNumBateriasBase(): ?int
    {
        return $this->numBateriasBase;
    }

    public function setNumBateriasBase(?int $numBateriasBase): static
    {
        $this->numBateriasBase = $numBateriasBase;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getRequestedVehicles(): Collection
    {
        return $this->requestedVehicles;
    }

    public function addRequestedVehicle(Vehicle $vehicle): static
    {
        if (!$this->requestedVehicles->contains($vehicle)) {
            $this->requestedVehicles->add($vehicle);
        }

        return $this;
    }

    public function removeRequestedVehicle(Vehicle $vehicle): static
    {
        $this->requestedVehicles->removeElement($vehicle);

        return $this;
    }

    /**
     * @return Collection<int, ServiceVehicle>
     */
    public function getServiceVehicles(): Collection
    {
        return $this->serviceVehicles;
    }

    public function addServiceVehicle(ServiceVehicle $serviceVehicle): static
    {
        if (!$this->serviceVehicles->contains($serviceVehicle)) {
            $this->serviceVehicles->add($serviceVehicle);
            $serviceVehicle->setService($this);
        }

        return $this;
    }

    public function removeServiceVehicle(ServiceVehicle $serviceVehicle): static
    {
        if ($this->serviceVehicles->removeElement($serviceVehicle)) {
            // set the owning side to null (unless already changed)
            if ($serviceVehicle->getService() === $this) {
                $serviceVehicle->setService(null);
            }
        }

        return $this;
    }
}