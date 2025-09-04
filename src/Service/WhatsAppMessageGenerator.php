<?php

namespace App\Service;

use App\Entity\Service;
use Symfony\Contracts\Translation\TranslatorInterface;

class WhatsAppMessageGenerator
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function createMessage(Service $service): string
    {
        $message = [];

        // Helper for date formatting
        $dateFormatter = new \IntlDateFormatter(
            'es_ES',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            null,
            null,
            'EEEE d \'de\' MMMM'
        );

        // Header
        $message[] = "*" . $dateFormatter->format($service->getStartDate()) . "*";
        $message[] = "*" . $service->getTitle() . "*";
        $message[] = ""; // Newline

        // Timings
        if ($service->getTimeAtBase()) {
            $message[] = "H. Base: " . $service->getTimeAtBase()->format('H:i');
        }
        if ($service->getDepartureTime()) {
            $message[] = "Hora de Salida: " . $service->getDepartureTime()->format('H:i');
        }
        if ($service->getEndDate()) {
            $message[] = "Fecha y Hora de Fin: " . $service->getEndDate()->format('d/m/Y H:i');
        }
        $message[] = ""; // Newline

        // Location
        if ($service->getLocality()) {
            $message[] = "Lugar: " . $service->getLocality();
            $message[] = ""; // Newline
        }

        // Conditional resources
        $resources = [];
        if ($service->isHasProvisions()) {
            $resources[] = "Avituallamiento";
        }

        $ambulances = [];
        if ($service->getNumSvb() > 0) {
            $ambulances[] = "SVB (" . $service->getNumSvb() . ")";
        }
        if ($service->getNumSva() > 0) {
            $ambulances[] = "SVA (" . $service->getNumSva() . ")";
        }
        if ($service->getNumSvae() > 0) {
            $ambulances[] = "SVAE (" . $service->getNumSvae() . ")";
        }
        if (!empty($ambulances)) {
            $resources[] = "Ambulancias: " . implode(', ', $ambulances);
        }

        $medicalStaff = [];
        if ($service->getNumDoctors() > 0) {
            $medicalStaff[] = "Médicos (" . $service->getNumDoctors() . ")";
        }
        if ($service->getNumNurses() > 0) {
            $medicalStaff[] = "Enfermería (" . $service->getNumNurses() . ")";
        }
        if (!empty($medicalStaff)) {
            $resources[] = "Personal Sanitario: " . implode(', ', $medicalStaff);
        }

        if ($service->getAfluencia()) {
            $resources[] = "Afluencia: " . ucfirst($service->getAfluencia());
        }

        if ($service->isHasFieldHospital()) {
            $resources[] = "Hospital de Campaña";
        }

        if (!empty($resources)) {
            $message[] = implode("\n", $resources);
            $message[] = ""; // Newline
        }

        // Tasks
        if ($service->getTasks()) {
            $message[] = "*Tareas:*";
            $message[] = strip_tags($service->getTasks());
            $message[] = ""; // Newline
        }

        // Decision
        if ($service->getDescription()) {
            $message[] = "*Decisión:*";
            $message[] = strip_tags($service->getDescription());
        }

        return implode("\n", $message);
    }
}
