<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    public const STATUS_ACTIVE = 'Activo';
    public const STATUS_SUSPENDED = 'Suspensión';
    public const STATUS_INACTIVE = 'Baja';
    public const STATUS_PENDING = 'pending';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 15, unique: true, nullable: true)]
    private ?string $dni = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $streetType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $province = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson1 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson2 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $employmentStatus = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $drivingLicenses = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $drivingLicenseExpiryDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motivation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $howKnown = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $hasVolunteeredBefore = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $previousVolunteeringInstitutions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $otherQualifications = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $navigationLicenses = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specificQualifications = null;

    #[ORM\Column(length: 100)]
    private ?string $role = null;

    #[ORM\Column(length: 20)]
    private ?string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\Column(length: 255)]
    private ?string $specialization = null;

    #[ORM\OneToOne(inversedBy: 'volunteer', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: AssistanceConfirmation::class)]
    private Collection $assistanceConfirmations;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: VolunteerService::class)]
    private Collection $volunteerServices;

    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
        $this->volunteerServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(?string $dni): static
    {
        $this->dni = $dni;
        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getStreetType(): ?string
    {
        return $this->streetType;
    }

    public function setStreetType(?string $streetType): static
    {
        $this->streetType = $streetType;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getContactPerson1(): ?string
    {
        return $this->contactPerson1;
    }

    public function setContactPerson1(?string $contactPerson1): static
    {
        $this->contactPerson1 = $contactPerson1;
        return $this;
    }

    public function getContactPhone1(): ?string
    {
        return $this->contactPhone1;
    }

    public function setContactPhone1(?string $contactPhone1): static
    {
        $this->contactPhone1 = $contactPhone1;
        return $this;
    }

    public function getContactPerson2(): ?string
    {
        return $this->contactPerson2;
    }

    public function setContactPerson2(?string $contactPerson2): static
    {
        $this->contactPerson2 = $contactPerson2;
        return $this;
    }

    public function getContactPhone2(): ?string
    {
        return $this->contactPhone2;
    }

    public function setContactPhone2(?string $contactPhone2): static
    {
        $this->contactPhone2 = $contactPhone2;
        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;
        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;
        return $this;
    }

    public function getEmploymentStatus(): ?string
    {
        return $this->employmentStatus;
    }

    public function setEmploymentStatus(?string $employmentStatus): static
    {
        $this->employmentStatus = $employmentStatus;
        return $this;
    }

    public function getDrivingLicenses(): array
    {
        return $this->drivingLicenses ?? [];
    }

    public function setDrivingLicenses(?array $drivingLicenses): static
    {
        $this->drivingLicenses = $drivingLicenses;
        return $this;
    }

    public function getDrivingLicenseExpiryDate(): ?\DateTimeInterface
    {
        return $this->drivingLicenseExpiryDate;
    }

    public function setDrivingLicenseExpiryDate(?\DateTimeInterface $drivingLicenseExpiryDate): static
    {
        $this->drivingLicenseExpiryDate = $drivingLicenseExpiryDate;
        return $this;
    }

    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    public function setLanguages(?string $languages): static
    {
        $this->languages = $languages;
        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(?string $motivation): static
    {
        $this->motivation = $motivation;
        return $this;
    }

    public function getHowKnown(): ?string
    {
        return $this->howKnown;
    }

    public function setHowKnown(?string $howKnown): static
    {
        $this->howKnown = $howKnown;
        return $this;
    }

    public function getHasVolunteeredBefore(): ?bool
    {
        return $this->hasVolunteeredBefore;
    }

    public function setHasVolunteeredBefore(?bool $hasVolunteeredBefore): static
    {
        $this->hasVolunteeredBefore = $hasVolunteeredBefore;
        return $this;
    }

    public function getPreviousVolunteeringInstitutions(): ?string
    {
        return $this->previousVolunteeringInstitutions;
    }

    public function setPreviousVolunteeringInstitutions(?string $previousVolunteeringInstitutions): static
    {
        $this->previousVolunteeringInstitutions = $previousVolunteeringInstitutions;
        return $this;
    }

    public function getOtherQualifications(): ?string
    {
        return $this->otherQualifications;
    }

    public function setOtherQualifications(?string $otherQualifications): static
    {
        $this->otherQualifications = $otherQualifications;
        return $this;
    }

    public function getNavigationLicenses(): array
    {
        return $this->navigationLicenses ?? [];
    }

    public function setNavigationLicenses(?array $navigationLicenses): static
    {
        $this->navigationLicenses = $navigationLicenses;
        return $this;
    }

    public function getSpecificQualifications(): array
    {
        return $this->specificQualifications ?? [];
    }

    public function setSpecificQualifications(?array $specificQualifications): static
    {
        $this->specificQualifications = $specificQualifications;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeInterface $joinDate): static
    {
        $this->joinDate = $joinDate;
        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(string $specialization): static
    {
        $this->specialization = $specialization;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

   public function setUser(?User $user): static {
    if ($this->user === $user) {
        return $this;
    }

    $this->user = $user;

    if ($user !== null && $user->getVolunteer() !== $this) {
        $user->setVolunteer($this);
    }

    return $this;
}

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

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
            $assistanceConfirmation->setVolunteer($this);
        }

        return $this;
    }

    public function removeAssistanceConfirmation(AssistanceConfirmation $assistanceConfirmation): static
    {
        if ($this->assistanceConfirmations->removeElement($assistanceConfirmation)) {
            // set the owning side to null (unless already changed)
            if ($assistanceConfirmation->getVolunteer() === $this) {
                $assistanceConfirmation->setVolunteer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VolunteerService>
     */
    public function getVolunteerServices(): Collection
    {
        return $this->volunteerServices;
    }

    public function addVolunteerService(VolunteerService $volunteerService): static
    {
        if (!$this->volunteerServices->contains($volunteerService)) {
            $this->volunteerServices->add($volunteerService);
            $volunteerService->setVolunteer($this);
        }

        return $this;
    }

    public function removeVolunteerService(VolunteerService $volunteerService): static
    {
        if ($this->volunteerServices->removeElement($volunteerService)) {
            // set the owning side to null (unless already changed)
            if ($volunteerService->getVolunteer() === $this) {
                $volunteerService->setVolunteer(null);
            }
        }

        return $this;
    }
}