<?php

namespace App\Twig;

use App\Repository\VolunteerRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $volunteerRepository;

    public function __construct(VolunteerRepository $volunteerRepository)
    {
        $this->volunteerRepository = $volunteerRepository;
    }

    public function getGlobals(): array
    {
        return [
            'pending_volunteer_count' => $this->volunteerRepository->countPendingVolunteers(),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('format_spanish_date', [$this, 'formatSpanishDate']),
        ];
    }

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