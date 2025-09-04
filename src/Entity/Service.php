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

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $recipients = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $collaborationWithOtherServices = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requester = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: AssistanceConfirmation::class, orphanRemoval: true)]
    private Collection $assistanceConfirmations;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $afluencia = null;

    #[ORM\Column(nullable: true)]
    private ?int $numSvb = null;

    #[ORM\Column(nullable: true)]
    private ?int $numSva = null;

    #[ORM\Column(nullable: true)]
    private ?int $numSvae = null;

    #[ORM\Column(nullable: true)]
    private ?int $numMedical = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasFieldHospital = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tasks = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasProvisions = null;

    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
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

    public function isCollaborationWithOtherServices(): bool
    {
        return $this->collaborationWithOtherServices;
    }

    public function setCollaborationWithOtherServices(bool $collaborationWithOtherServices): static
    {
        $this->collaborationWithOtherServices = $collaborationWithOtherServices;

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

    /**
     * @return Collection<int, AssistanceConfirmation>
     */
    public function getAssistanceConfirmations(): Collection
    {
        return $this->assistanceConfirmations;
    }

    public function addAssistanceConfirmation(AssistanceConfirmation $assistanceConfirmation): static
    {
        if (!$this->assistanceConfirmations->contains($assistanceConfirmation)) {
            $this->assistanceConfirmations->add($assistanceConfirmation);
            $assistanceConfirmation->setService($this);
        }

        return $this;
    }

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

    public function getAfluencia(): ?string
    {
        return $this->afluencia;
    }

    public function setAfluencia(?string $afluencia): static
    {
        $this->afluencia = $afluencia;

        return $this;
    }

    public function getNumSvb(): ?int
    {
        return $this->numSvb;
    }

    public function setNumSvb(?int $numSvb): static
    {
        $this->numSvb = $numSvb;

        return $this;
    }

    public function getNumSva(): ?int
    {
        return $this->numSva;
    }

    public function setNumSva(?int $numSva): static
    {
        $this->numSva = $numSva;

        return $this;
    }

    public function getNumSvae(): ?int
    {
        return $this->numSvae;
    }

    public function setNumSvae(?int $numSvae): static
    {
        $this->numSvae = $numSvae;

        return $this;
    }

    public function getNumMedical(): ?int
    {
        return $this->numMedical;
    }

    public function setNumMedical(?int $numMedical): static
    {
        $this->numMedical = $numMedical;

        return $this;
    }

    public function isHasFieldHospital(): ?bool
    {
        return $this->hasFieldHospital;
    }

    public function setHasFieldHospital(?bool $hasFieldHospital): static
    {
        $this->hasFieldHospital = $hasFieldHospital;

        return $this;
    }

    public function getTasks(): ?string
    {
        return $this->tasks;
    }

    public function setTasks(?string $tasks): static
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function isHasProvisions(): ?bool
    {
        return $this->hasProvisions;
    }

    public function setHasProvisions(?bool $hasProvisions): static
    {
        $this->hasProvisions = $hasProvisions;

        return $this;
    }
}