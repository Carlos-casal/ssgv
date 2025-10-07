<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a volunteer's profile, containing personal information, qualifications, and status.
 * This entity is linked to a User account for authentication.
 */
#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    /** @var string Status for an active volunteer. */
    public const STATUS_ACTIVE = 'Activo';
    /** @var string Status for a suspended volunteer. */
    public const STATUS_SUSPENDED = 'Suspensión';
    /** @var string Status for an inactive (resigned) volunteer. */
    public const STATUS_INACTIVE = 'Baja';
    /** @var string Status for a new registration pending approval. */
    public const STATUS_PENDING = 'pending';

    /**
     * @var int|null The unique identifier for the volunteer.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The first name of the volunteer.
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string|null The last name of the volunteer.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    /**
     * @var string|null The phone number of the volunteer.
     */
    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    /**
     * @var string|null The DNI (National Identity Document) of the volunteer. Must be unique.
     */
    #[ORM\Column(length: 15, unique: true, nullable: true)]
    private ?string $dni = null;

    /**
     * @var \DateTimeInterface|null The date of birth of the volunteer.
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    /**
     * @var string|null The type of street for the address (e.g., "Calle", "Avenida").
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $streetType = null;

    /**
     * @var string|null The main address line.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @var string|null The postal code.
     */
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null;

    /**
     * @var string|null The province.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $province = null;

    /**
     * @var string|null The city.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    /**
     * @var string|null The name of the primary emergency contact.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson1 = null;

    /**
     * @var string|null The phone number of the primary emergency contact.
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone1 = null;

    /**
     * @var string|null The name of the secondary emergency contact.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson2 = null;

    /**
     * @var string|null The phone number of the secondary emergency contact.
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone2 = null;

    /**
     * @var string|null Information about any allergies the volunteer has.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null;

    /**
     * @var string|null The volunteer's profession.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profession = null;

    /**
     * @var string|null The volunteer's current employment status.
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $employmentStatus = null;

    /**
     * @var array|null An array of driving licenses the volunteer holds.
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $drivingLicenses = null;

    /**
     * @var \DateTimeInterface|null The expiry date of the driving license.
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $drivingLicenseExpiryDate = null;

    /**
     * @var string|null Languages spoken by the volunteer.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null;

    /**
     * @var string|null The volunteer's motivation for joining.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motivation = null;

    /**
     * @var string|null How the volunteer heard about the organization.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $howKnown = null;

    /**
     * @var bool|null Whether the volunteer has prior volunteering experience.
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $hasVolunteeredBefore = false;

    /**
     * @var string|null Names of institutions where the volunteer has previously volunteered.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $previousVolunteeringInstitutions = null;

    /**
     * @var string|null Other relevant qualifications or skills.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $otherQualifications = null;

    /**
     * @var array|null An array of navigation licenses the volunteer holds.
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $navigationLicenses = null;

    /**
     * @var array|null An array of specific, certified qualifications.
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specificQualifications = null;

    /**
     * @var string|null The role of the volunteer within the organization (e.g., "Jefe de Unidad").
     */
    #[ORM\Column(length: 100)]
    private ?string $role = null;

    /**
     * @var string|null The current status of the volunteer (e.g., "Activo", "Baja").
     */
    #[ORM\Column(length: 20)]
    private ?string $status = self::STATUS_ACTIVE;

    /**
     * @var \DateTimeInterface|null The date the volunteer joined the organization.
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $joinDate = null;

    /**
     * @var \DateTimeInterface|null The date when the volunteer's status last changed.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $statusChangeDate = null;

    /**
     * @var string|null The specialization of the volunteer (e.g., "Sanitario", "Logística").
     */
    #[ORM\Column(length: 255)]
    private ?string $specialization = null;

    /**
     * @var User|null The User entity associated with this volunteer profile.
     */
    #[ORM\OneToOne(inversedBy: 'volunteer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var string|null The filename of the volunteer's profile picture.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroIdentificacion = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $indicativo = null;

    #[ORM\Column]
    private ?bool $habilitadoConducir = false;

    /**
     * @var Collection<int, AssistanceConfirmation> A collection of this volunteer's assistance confirmations.
     */
    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: AssistanceConfirmation::class)]
    private Collection $assistanceConfirmations;

    /**
     * @var Collection<int, VolunteerService> A collection of this volunteer's service participation records.
     */
    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: VolunteerService::class)]
    private Collection $volunteerServices;

    /**
     * Initializes collections.
     */
    public function __construct()
    {
        $this->assistanceConfirmations = new ArrayCollection();
        $this->volunteerServices = new ArrayCollection();
    }

    /**
     * Gets the unique identifier for the volunteer.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the first name of the volunteer.
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the first name of the volunteer.
     * @param string $name The first name.
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the last name of the volunteer.
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets the last name of the volunteer.
     * @param string|null $lastName The last name.
     * @return static
     */
    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Gets the phone number of the volunteer.
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Sets the phone number of the volunteer.
     * @param string $phone The phone number.
     * @return static
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Gets the DNI of the volunteer.
     * @return string|null
     */
    public function getDni(): ?string
    {
        return $this->dni;
    }

    /**
     * Sets the DNI of the volunteer.
     * @param string|null $dni The DNI.
     * @return static
     */
    public function setDni(?string $dni): static
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * Gets the date of birth of the volunteer.
     * @return \DateTimeInterface|null
     */
    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets the date of birth of the volunteer.
     * @param \DateTimeInterface|null $dateOfBirth The date of birth.
     * @return static
     */
    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * Gets the street type for the address.
     * @return string|null
     */
    public function getStreetType(): ?string
    {
        return $this->streetType;
    }

    /**
     * Sets the street type for the address.
     * @param string|null $streetType The street type.
     * @return static
     */
    public function setStreetType(?string $streetType): static
    {
        $this->streetType = $streetType;
        return $this;
    }

    /**
     * Gets the address.
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Sets the address.
     * @param string|null $address The address.
     * @return static
     */
    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets the postal code.
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Sets the postal code.
     * @param string|null $postalCode The postal code.
     * @return static
     */
    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * Gets the province.
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->province;
    }

    /**
     * Sets the province.
     * @param string|null $province The province.
     * @return static
     */
    public function setProvince(?string $province): static
    {
        $this->province = $province;
        return $this;
    }

    /**
     * Gets the city.
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Sets the city.
     * @param string|null $city The city.
     * @return static
     */
    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Gets the primary emergency contact person's name.
     * @return string|null
     */
    public function getContactPerson1(): ?string
    {
        return $this->contactPerson1;
    }

    /**
     * Sets the primary emergency contact person's name.
     * @param string|null $contactPerson1 The contact's name.
     * @return static
     */
    public function setContactPerson1(?string $contactPerson1): static
    {
        $this->contactPerson1 = $contactPerson1;
        return $this;
    }

    /**
     * Gets the primary emergency contact person's phone number.
     * @return string|null
     */
    public function getContactPhone1(): ?string
    {
        return $this->contactPhone1;
    }

    /**
     * Sets the primary emergency contact person's phone number.
     * @param string|null $contactPhone1 The contact's phone number.
     * @return static
     */
    public function setContactPhone1(?string $contactPhone1): static
    {
        $this->contactPhone1 = $contactPhone1;
        return $this;
    }

    /**
     * Gets the secondary emergency contact person's name.
     * @return string|null
     */
    public function getContactPerson2(): ?string
    {
        return $this->contactPerson2;
    }

    /**
     * Sets the secondary emergency contact person's name.
     * @param string|null $contactPerson2 The contact's name.
     * @return static
     */
    public function setContactPerson2(?string $contactPerson2): static
    {
        $this->contactPerson2 = $contactPerson2;
        return $this;
    }

    /**
     * Gets the secondary emergency contact person's phone number.
     * @return string|null
     */
    public function getContactPhone2(): ?string
    {
        return $this->contactPhone2;
    }

    /**
     * Sets the secondary emergency contact person's phone number.
     * @param string|null $contactPhone2 The contact's phone number.
     * @return static
     */
    public function setContactPhone2(?string $contactPhone2): static
    {
        $this->contactPhone2 = $contactPhone2;
        return $this;
    }

    /**
     * Gets information about allergies.
     * @return string|null
     */
    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    /**
     * Sets information about allergies.
     * @param string|null $allergies The allergy information.
     * @return static
     */
    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;
        return $this;
    }

    /**
     * Gets the volunteer's profession.
     * @return string|null
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     * Sets the volunteer's profession.
     * @param string|null $profession The profession.
     * @return static
     */
    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * Gets the volunteer's employment status.
     * @return string|null
     */
    public function getEmploymentStatus(): ?string
    {
        return $this->employmentStatus;
    }

    /**
     * Sets the volunteer's employment status.
     * @param string|null $employmentStatus The employment status.
     * @return static
     */
    public function setEmploymentStatus(?string $employmentStatus): static
    {
        $this->employmentStatus = $employmentStatus;
        return $this;
    }

    /**
     * Gets the driving licenses held by the volunteer.
     * @return array
     */
    public function getDrivingLicenses(): array
    {
        return $this->drivingLicenses ?? [];
    }

    /**
     * Sets the driving licenses held by the volunteer.
     * @param array|null $drivingLicenses An array of licenses.
     * @return static
     */
    public function setDrivingLicenses(?array $drivingLicenses): static
    {
        $this->drivingLicenses = $drivingLicenses;
        return $this;
    }

    /**
     * Gets the expiry date of the driving license.
     * @return \DateTimeInterface|null
     */
    public function getDrivingLicenseExpiryDate(): ?\DateTimeInterface
    {
        return $this->drivingLicenseExpiryDate;
    }

    /**
     * Sets the expiry date of the driving license.
     * @param \DateTimeInterface|null $drivingLicenseExpiryDate The expiry date.
     * @return static
     */
    public function setDrivingLicenseExpiryDate(?\DateTimeInterface $drivingLicenseExpiryDate): static
    {
        $this->drivingLicenseExpiryDate = $drivingLicenseExpiryDate;
        return $this;
    }

    /**
     * Gets the languages spoken by the volunteer.
     * @return string|null
     */
    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    /**
     * Sets the languages spoken by the volunteer.
     * @param string|null $languages The languages.
     * @return static
     */
    public function setLanguages(?string $languages): static
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * Gets the volunteer's motivation for joining.
     * @return string|null
     */
    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    /**
     * Sets the volunteer's motivation for joining.
     * @param string|null $motivation The motivation text.
     * @return static
     */
    public function setMotivation(?string $motivation): static
    {
        $this->motivation = $motivation;
        return $this;
    }

    /**
     * Gets how the volunteer heard about the organization.
     * @return string|null
     */
    public function getHowKnown(): ?string
    {
        return $this->howKnown;
    }

    /**
     * Sets how the volunteer heard about the organization.
     * @param string|null $howKnown The source.
     * @return static
     */
    public function setHowKnown(?string $howKnown): static
    {
        $this->howKnown = $howKnown;
        return $this;
    }

    /**
     * Checks if the volunteer has prior volunteering experience.
     * @return bool|null
     */
    public function getHasVolunteeredBefore(): ?bool
    {
        return $this->hasVolunteeredBefore;
    }

    /**
     * Sets whether the volunteer has prior volunteering experience.
     * @param bool|null $hasVolunteeredBefore True if they have experience.
     * @return static
     */
    public function setHasVolunteeredBefore(?bool $hasVolunteeredBefore): static
    {
        $this->hasVolunteeredBefore = $hasVolunteeredBefore;
        return $this;
    }

    /**
     * Gets the names of institutions where the volunteer has previously volunteered.
     * @return string|null
     */
    public function getPreviousVolunteeringInstitutions(): ?string
    {
        return $this->previousVolunteeringInstitutions;
    }

    /**
     * Sets the names of institutions where the volunteer has previously volunteered.
     * @param string|null $previousVolunteeringInstitutions The names of institutions.
     * @return static
     */
    public function setPreviousVolunteeringInstitutions(?string $previousVolunteeringInstitutions): static
    {
        $this->previousVolunteeringInstitutions = $previousVolunteeringInstitutions;
        return $this;
    }

    /**
     * Gets other relevant qualifications.
     * @return string|null
     */
    public function getOtherQualifications(): ?string
    {
        return $this->otherQualifications;
    }

    /**
     * Sets other relevant qualifications.
     * @param string|null $otherQualifications The qualifications.
     * @return static
     */
    public function setOtherQualifications(?string $otherQualifications): static
    {
        $this->otherQualifications = $otherQualifications;
        return $this;
    }

    /**
     * Gets the navigation licenses held by the volunteer.
     * @return array
     */
    public function getNavigationLicenses(): array
    {
        return $this->navigationLicenses ?? [];
    }

    /**
     * Sets the navigation licenses held by the volunteer.
     * @param array|null $navigationLicenses An array of licenses.
     * @return static
     */
    public function setNavigationLicenses(?array $navigationLicenses): static
    {
        $this->navigationLicenses = $navigationLicenses;
        return $this;
    }

    /**
     * Gets specific, certified qualifications.
     * @return array
     */
    public function getSpecificQualifications(): array
    {
        return $this->specificQualifications ?? [];
    }

    /**
     * Sets specific, certified qualifications.
     * @param array|null $specificQualifications An array of qualifications.
     * @return static
     */
    public function setSpecificQualifications(?array $specificQualifications): static
    {
        $this->specificQualifications = $specificQualifications;
        return $this;
    }

    /**
     * Gets the role of the volunteer.
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * Sets the role of the volunteer.
     * @param string $role The role.
     * @return static
     */
    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Gets the current status of the volunteer.
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets the current status of the volunteer.
     * @param string $status The new status.
     * @return static
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;
        $this->setStatusChangeDate(new \DateTime());

        return $this;
    }

    /**
     * Gets the date the volunteer joined.
     * @return \DateTimeInterface|null
     */
    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    /**
     * Sets the date the volunteer joined.
     * @param \DateTimeInterface $joinDate The join date.
     * @return static
     */
    public function setJoinDate(\DateTimeInterface $joinDate): static
    {
        $this->joinDate = $joinDate;
        return $this;
    }

    /**
     * Gets the specialization of the volunteer.
     * @return string|null
     */
    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    /**
     * Sets the specialization of the volunteer.
     * @param string $specialization The specialization.
     * @return static
     */
    public function setSpecialization(string $specialization): static
    {
        $this->specialization = $specialization;
        return $this;
    }

    /**
     * Gets the associated User entity.
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the associated User entity and maintains the bidirectional relationship.
     * @param User|null $user The User entity.
     * @return static
     */
    public function setUser(?User $user): static
    {
        if ($this->user === $user) {
            return $this;
        }

        $this->user = $user;

        if ($user !== null && $user->getVolunteer() !== $this) {
            $user->setVolunteer($this);
        }

        return $this;
    }

    /**
     * Gets the date of the last status change.
     * @return \DateTimeInterface|null
     */
    public function getStatusChangeDate(): ?\DateTimeInterface
    {
        return $this->statusChangeDate;
    }

    /**
     * Sets the date of the last status change.
     * @param \DateTimeInterface|null $statusChangeDate
     * @return static
     */
    public function setStatusChangeDate(?\DateTimeInterface $statusChangeDate): static
    {
        $this->statusChangeDate = $statusChangeDate;

        return $this;
    }

    /**
     * Gets the filename of the profile picture.
     * @return string|null
     */
    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    /**
     * Sets the filename of the profile picture.
     * @param string|null $profilePicture The filename.
     * @return static
     */
    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Gets the collection of assistance confirmations for this volunteer.
     * @return Collection<int, AssistanceConfirmation>
     */
    public function getAssistanceConfirmations(): Collection
    {
        return $this->assistanceConfirmations;
    }

    /**
     * Adds an assistance confirmation for this volunteer.
     * @param AssistanceConfirmation $assistanceConfirmation The confirmation to add.
     * @return static
     */
    public function addAssistanceConfirmation(AssistanceConfirmation $assistanceConfirmation): static
    {
        if (!$this->assistanceConfirmations->contains($assistanceConfirmation)) {
            $this->assistanceConfirmations->add($assistanceConfirmation);
            $assistanceConfirmation->setVolunteer($this);
        }

        return $this;
    }

    /**
     * Removes an assistance confirmation for this volunteer.
     * @param AssistanceConfirmation $assistanceConfirmation The confirmation to remove.
     * @return static
     */
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
     * Gets the collection of service participation records for this volunteer.
     * @return Collection<int, VolunteerService>
     */
    public function getVolunteerServices(): Collection
    {
        return $this->volunteerServices;
    }

    /**
     * Adds a service participation record for this volunteer.
     * @param VolunteerService $volunteerService The record to add.
     * @return static
     */
    public function addVolunteerService(VolunteerService $volunteerService): static
    {
        if (!$this->volunteerServices->contains($volunteerService)) {
            $this->volunteerServices->add($volunteerService);
            $volunteerService->setVolunteer($this);
        }

        return $this;
    }

    /**
     * Removes a service participation record for this volunteer.
     * @param VolunteerService $volunteerService The record to remove.
     * @return static
     */
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

    /**
     * Finds the specific VolunteerService record for a given Service.
     * @param Service $service The service to find the record for.
     * @return VolunteerService|null The corresponding VolunteerService record, or null if not found.
     */
    public function getNumeroIdentificacion(): ?string
    {
        return $this->numeroIdentificacion;
    }

    public function setNumeroIdentificacion(?string $numeroIdentificacion): static
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    public function getIndicativo(): ?string
    {
        return $this->indicativo;
    }

    public function setIndicativo(?string $indicativo): static
    {
        $this->indicativo = $indicativo;

        return $this;
    }

    public function isHabilitadoConducir(): ?bool
    {
        return $this->habilitadoConducir;
    }

    public function setHabilitadoConducir(bool $habilitadoConducir): static
    {
        $this->habilitadoConducir = $habilitadoConducir;

        return $this;
    }

    public function getVolunteerServiceForService(Service $service): ?VolunteerService
    {
        foreach ($this->volunteerServices as $vs) {
            if ($vs->getService() === $service) {
                return $vs;
            }
        }
        return null;
    }
}