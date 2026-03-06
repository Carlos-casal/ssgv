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

            // 1. Annual counter: EXP-[YEAR]-[ANUAL_NUMBER]
            $anualNumber = $this->serviceRepository->getNextSequentialNumber($year);
            $expPart = sprintf('EXP-%d-%03d', $year, $anualNumber);

            // 2. Class counter: [TYPE]-[CAT]-[SUB]-[CLASS_NUMBER]
            $type = $service->getType();
            $cat = $service->getCategory();
            $sub = $service->getSubcategory();

            $typeCode = $type ? ($type->getCode() ?: strtoupper(substr($type->getName(), 0, 3))) : 'XXX';
            $catCode = $cat ? ($cat->getCode() ?: strtoupper(substr($cat->getName(), 0, 3))) : 'XXX';
            $subCode = $sub ? ($sub->getCode() ?: strtoupper(substr($sub->getName(), 0, 3))) : 'XXX';

            $classNumber = $this->serviceRepository->getClassSequentialNumber($type, $cat, $sub);
            $classPart = sprintf('%s-%s-%s-%03d', $typeCode, $catCode, $subCode, $classNumber);

            // 3. Global counter: REG-[TOTAL_NUMBER]
            $totalNumber = $this->serviceRepository->getGlobalTotalCount() + 1;
            $regPart = sprintf('REG-%03d', $totalNumber);

            // Final: EXP-2025-001 | PRE-DEP-FUT-001 | REG-001
            $generatedId = sprintf('%s | %s | %s', $expPart, $classPart, $regPart);

            $service->setNumeration($generatedId);
        }
    }
}
