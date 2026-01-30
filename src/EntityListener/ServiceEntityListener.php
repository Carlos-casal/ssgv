<?php

namespace App\EntityListener;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Service::class)]
class ServiceEntityListener
{
    public function __construct(private ServiceRepository $serviceRepository)
    {
    }

    public function prePersist(Service $service, PrePersistEventArgs $event): void
    {
        if (!$service->getNumeration()) {
            $year = $service->getStartDate() ? (int)$service->getStartDate()->format('Y') : (int)date('Y');

            // Format 2026-00X
            $lastNumber = $this->serviceRepository->getNextSequentialNumber($year);
            $generatedId = sprintf('%d-%03d', $year, $lastNumber);

            $service->setNumeration($generatedId);
        }
    }
}
