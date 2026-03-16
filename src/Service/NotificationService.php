<?php

namespace App\Service;

use App\Repository\MaterialRepository;
use App\Entity\Material;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class NotificationService
{
    private MaterialRepository $materialRepository;
    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        MaterialRepository $materialRepository,
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->materialRepository = $materialRepository;
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getAlerts(): array
    {
        $alerts = [];

        // 1. Optimized Low Stock Check
        $lowStockMaterials = $this->materialRepository->findLowStockMaterials();
        foreach ($lowStockMaterials as $material) {
            $alerts[] = [
                'type' => 'low_stock',
                'title' => 'Stock Bajo',
                'message' => 'El material "' . $material->getName() . '" está por debajo del stock mínimo.',
                'link' => '/material/' . $material->getId(),
                'severity' => 'warning',
                'createdAt' => new \DateTimeImmutable()
            ];
        }

        // 2. Optimized Expiry Check (up to 6 months for all statuses)
        $threshold = (new \DateTimeImmutable('today'))->modify('+6 months');
        $expiringMaterials = $this->materialRepository->findExpiringMaterials($threshold);

        foreach ($expiringMaterials as $material) {
            $status = $material->getExpirationStatus();
            if ($status === 'red') {
                $alerts[] = [
                    'type' => 'expired',
                    'title' => 'Material Caducado',
                    'message' => 'El material "' . $material->getName() . '" ha caducado.',
                    'link' => '/material/' . $material->getId(),
                    'severity' => 'danger',
                    'createdAt' => new \DateTimeImmutable()
                ];
            } elseif ($status === 'orange') {
                $alerts[] = [
                    'type' => 'expiring_soon',
                    'title' => 'Próxima Caducidad',
                    'message' => 'El material "' . $material->getName() . '" caducará pronto.',
                    'link' => '/material/' . $material->getId(),
                    'severity' => 'warning',
                    'createdAt' => new \DateTimeImmutable()
                ];
            }
        }

        // 3. Combined with persistent notifications
        $user = $this->security->getUser();
        if ($user instanceof User) {
            $persistentNotifications = $this->notificationRepository->findBy(
                ['recipient' => $user],
                ['createdAt' => 'DESC']
            );

            foreach ($persistentNotifications as $notification) {
                $alerts[] = [
                    'id' => $notification->getId(),
                    'type' => 'persistent',
                    'sub_type' => $notification->getType(),
                    'title' => $notification->getTitle(),
                    'message' => $notification->getMessage(),
                    'link' => $notification->getLink(),
                    'severity' => $notification->getSeverity(),
                    'createdAt' => $notification->getCreatedAt(),
                    'isRead' => $notification->isRead(),
                ];
            }
        }

        // Sort alerts by createdAt (System alerts use 'now' effectively)
        usort($alerts, function($a, $b) {
            $dateA = $a['createdAt'] ?? new \DateTimeImmutable();
            $dateB = $b['createdAt'] ?? new \DateTimeImmutable();
            return $dateB <=> $dateA;
        });

        return $alerts;
    }

    public function createNotification(User $user, string $title, string $message, string $type = 'info', string $severity = 'info', ?string $link = null): Notification
    {
        $notification = new Notification();
        $notification->setRecipient($user);
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setSeverity($severity);
        $notification->setLink($link);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    public function markAsRead(int $id): void
    {
        $notification = $this->notificationRepository->find($id);
        if ($notification) {
            $notification->setIsRead(true);
            $this->entityManager->flush();
        }
    }

    public function markAllAsRead(User $user): void
    {
        $notifications = $this->notificationRepository->findBy(['recipient' => $user, 'isRead' => false]);
        foreach ($notifications as $notification) {
            $notification->setIsRead(true);
        }
        $this->entityManager->flush();
    }

    public function deleteNotification(int $id): void
    {
        $notification = $this->notificationRepository->find($id);
        if ($notification) {
            $this->entityManager->remove($notification);
            $this->entityManager->flush();
        }
    }

    public function deleteAll(User $user): void
    {
        $notifications = $this->notificationRepository->findBy(['recipient' => $user]);
        foreach ($notifications as $notification) {
            $this->entityManager->remove($notification);
        }
        $this->entityManager->flush();
    }
}
