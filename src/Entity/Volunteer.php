<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\DBal\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null; // Apellidos

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 15, unique: true, nullable: true)]
    private ?string $dni = null; // DNI

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null; // Fecha de Nacimiento

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $streetType = null; // Tipo de vía (ej. Calle, Avenida)

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null; // Dirección (nombre de la calle, número, piso, puerta)

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $postalCode = null; // Código Postal

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $province = null; // Provincia

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null; // Población

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson1 = null; // Persona de Contacto 1

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone1 = null; // Teléfono Persona de Contacto 1

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPerson2 = null; // Persona de Contacto 2

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $contactPhone2 = null; // Teléfono Persona de Contacto 2

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $allergies = null; // Tipo de Alergias / Consideraciones de salud

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profession = null; // Profesión

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $employmentStatus = null; // Situacion laboral

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $drivingLicenses = null; // Permiso de conducir (AHORA ?array y null por defecto)

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $drivingLicenseExpiryDate = null; // Fecha de caducidad permiso conducir

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $languages = null; // Idiomas

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motivation = null; // Motivos por los que quiere ser voluntario

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $howKnown = null; // ¿Cómo nos ha conocido?

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $hasVolunteeredBefore = null; // ¿Ha realizado funciones de voluntariado con anterioridad?

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $previousVolunteeringInstitutions = null; // En caso afirmativo, indique la institución o instituciones

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $otherQualifications = null; // Otros Títulos, lugar y año

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $navigationLicenses = null; // Permiso de navegación (AHORA ?array y null por defecto)

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specificQualifications = null; // Titulaciones específicas agrupadas (AHORA ?array y null por defecto)

    #[ORM\Column(length: 100)]
    private ?string $role = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'Activo';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\Column(length: 255)]
    private ?string $specialization = null;

    #[ORM\OneToOne(inversedBy: 'volunteer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

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

    // Asegúrate de que los getters de arrays siempre devuelvan un array (vacío si es null)
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

    // Asegúrate de que los getters de arrays siempre devuelvan un array (vacío si es null)
    public function getNavigationLicenses(): array
    {
        return $this->navigationLicenses ?? [];
    }

    public function setNavigationLicenses(?array $navigationLicenses): static
    {
        $this->navigationLicenses = $navigationLicenses;
        return $this;
    }

    // Asegúrate de que los getters de arrays siempre devuelvan un array (vacío si es null)
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
}