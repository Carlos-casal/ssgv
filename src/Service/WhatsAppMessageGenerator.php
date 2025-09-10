<?php

namespace App\Service;

use App\Entity\Service;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WhatsAppMessageGenerator
{
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
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
        if ($service->getStartDate()) {
            $message[] = "*" . $dateFormatter->format($service->getStartDate()) . "*";
        }
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

        // Description is now used for the 'Decision' part
        if ($service->getDescription()) {
            $message[] = "*Decisión:*";
            $message[] = strip_tags($service->getDescription());
            $message[] = ""; // Newline
        }

        // Attendance links
        if ($service->getId()) {
            $attendUrl = $this->urlGenerator->generate('app_service_attend', ['id' => $service->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $unattendUrl = $this->urlGenerator->generate('app_service_unattend', ['id' => $service->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            $message[] = "Por favor, confirma tu asistencia pulsando en uno de los siguientes enlaces:";
            $message[] = "✅ *Asisto* " . $attendUrl;
            $message[] = "❌ *No Asisto* " . $unattendUrl;
        } else {
            // Placeholder for the preview on the new service page
            $message[] = "Por favor, confirma tu asistencia (los enlaces se generarán al guardar):";
            $message[] = "✅ *Asisto*";
            $message[] = "❌ *No Asisto*";
        }


        return implode("\n", $message);
    }
}