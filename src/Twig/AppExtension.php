<?php

namespace App\Twig;

use App\Repository\VolunteerRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

/**
 * Custom Twig extension to provide global variables and custom filters to the templates.
 */
class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $volunteerRepository;

    /**
     * AppExtension constructor.
     *
     * @param VolunteerRepository $volunteerRepository The repository for volunteers, used to fetch global data.
     */
    public function __construct(VolunteerRepository $volunteerRepository)
    {
        $this->volunteerRepository = $volunteerRepository;
    }

    /**
     * Defines global variables available in all Twig templates.
     *
     * @return array An array of global variables.
     */
    public function getGlobals(): array
    {
        return [
            'pending_volunteer_count' => $this->volunteerRepository->countPendingVolunteers(),
            'now' => new \DateTime(),
        ];
    }

    /**
     * Defines custom filters available in Twig templates.
     *
     * @return TwigFilter[] An array of Twig filters.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_spanish_date', [$this, 'formatSpanishDate']),
        ];
    }

    /**
     * Formats a DateTime object into a Spanish-style short date string.
     * Example: "Lun, 01 de Ene de 24"
     *
     * @param \DateTimeInterface $date The date to format.
     * @return string The formatted date string.
     */
    public function formatSpanishDate(\DateTimeInterface $date): string
    {
        $days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        $dayOfWeek = $days[(int)$date->format('w')];
        $dayOfMonth = $date->format('d');
        $month = $months[(int)$date->format('n') - 1];
        $year = $date->format('y');

        return sprintf('%s, %s de %s de %s', $dayOfWeek, $dayOfMonth, $month, $year);
    }
}