<?php

namespace App\Service;

use App\Entity\Service;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A service responsible for generating formatted WhatsApp messages for service announcements.
 */
class WhatsAppMessageGenerator
{
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;

    /**
     * WhatsAppMessageGenerator constructor.
     *
     * @param TranslatorInterface $translator The translator service for internationalization.
     * @param UrlGeneratorInterface $urlGenerator The URL generator service to create absolute URLs.
     */
    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Creates a formatted, multi-line string suitable for a WhatsApp message from a Service entity.
     *
     * The message includes the service title, date, timings, location, required resources, tasks,
     * and direct links for volunteers to confirm or decline attendance.
     *
     * @param Service $service The service entity to generate the message for.
     * @return string The formatted WhatsApp message.
     */
    public function createMessage(Service $service): string
    {
        $message = [];

        // Header
        if ($service->getStartDate()) {
            $message[] = "*" . $this->formatSpanishDate($service->getStartDate()) . "*";
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
        if ($service->getNumSvb()) {
            $ambulances[] = "SVB";
        }
        if ($service->getNumSva()) {
            $ambulances[] = "SVA";
        }
        if ($service->getNumColectiva()) {
            $ambulances[] = "Colectiva";
        }
        if (!empty($ambulances)) {
            $resources[] = "Ambulancias: " . implode(', ', $ambulances);
        }

        $personnel = [];
        if ($service->getNumTes()) {
            $personnel[] = "TES";
        }
        if ($service->getNumTts()) {
            $personnel[] = "TTS";
        }
        if ($service->getNumDue()) {
            $personnel[] = "DUE/Enfermería";
        }
        if ($service->getNumDoctors()) {
            $personnel[] = "Médico";
        }
        if (!empty($personnel)) {
            $resources[] = "Personal: " . implode(', ', $personnel);
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

    /**
     * Formats a date in Spanish (e.g., "martes 24 de marzo") without depending on the Intl extension locale support,
     * which might be missing or limited in some environments (like the sandbox polyfill).
     */
    private function formatSpanishDate(\DateTimeInterface $date): string
    {
        $days = [
            'Sunday' => 'domingo', 'Monday' => 'lunes', 'Tuesday' => 'martes',
            'Wednesday' => 'miércoles', 'Thursday' => 'jueves', 'Friday' => 'viernes', 'Saturday' => 'sábado'
        ];
        $months = [
            'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril',
            'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto',
            'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
        ];

        $dayName = $days[$date->format('l')];
        $dayNum = $date->format('j');
        $monthName = $months[$date->format('F')];

        return sprintf('%s %d de %s', $dayName, $dayNum, $monthName);
    }
}