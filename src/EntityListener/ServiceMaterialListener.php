<?php

namespace App\EntityListener;

use App\Entity\ServiceMaterial;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ServiceMaterial::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ServiceMaterial::class)]
class ServiceMaterialListener
{
    public function prePersist(ServiceMaterial $serviceMaterial, LifecycleEventArgs $event): void
    {
        $this->updateUnitUsage($serviceMaterial);
    }

    public function preUpdate(ServiceMaterial $serviceMaterial, LifecycleEventArgs $event): void
    {
        $this->updateUnitUsage($serviceMaterial);
    }

    private function updateUnitUsage(ServiceMaterial $serviceMaterial): void
    {
        $unit = $serviceMaterial->getMaterialUnit();
        if ($unit) {
            $service = $serviceMaterial->getService();
            if ($service && $service->getEndDate()) {
                // Set last used to the end date of the service
                $unit->setLastUsedAt(\DateTimeImmutable::createFromInterface($service->getEndDate()));
            }
        }
    }
}
