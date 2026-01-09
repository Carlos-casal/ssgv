<?php

namespace App\Tests\Entity;

use App\Entity\Service;
use App\Entity\AssistanceConfirmation;
use App\Entity\VolunteerService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Service entity.
 * @group Entity
 */
class ServiceTest extends TestCase
{
    /**
     * Tests that a new Service object can be instantiated and its default values are correct.
     */
    public function testNewService(): void
    {
        $service = new Service();
        $this->assertInstanceOf(Service::class, $service);
        $this->assertNull($service->getId());
        $this->assertNull($service->getTitle());
        $this->assertNull($service->getStartDate());
        $this->assertNull($service->getEndDate());
        $this->assertFalse($service->isArchived());
        $this->assertInstanceOf(\Doctrine\Common\Collections\ArrayCollection::class, $service->getAssistanceConfirmations());
        $this->assertInstanceOf(\Doctrine\Common\Collections\ArrayCollection::class, $service->getVolunteerServices());
    }

    /**
     * Tests the getter and setter for the title property.
     */
    public function testGetSetTitle(): void
    {
        $service = new Service();
        $title = 'Test Service';
        $service->setTitle($title);

        $this->assertSame($title, $service->getTitle());
    }

    /**
     * Tests adding and removing assistance confirmations.
     */
    public function testAssistanceConfirmations(): void
    {
        $service = new Service();
        $confirmation = new AssistanceConfirmation();

        $this->assertCount(0, $service->getAssistanceConfirmations());
        $service->addAssistanceConfirmation($confirmation);
        $this->assertCount(1, $service->getAssistanceConfirmations());
        $this->assertSame($service, $confirmation->getService());

        $service->removeAssistanceConfirmation($confirmation);
        $this->assertCount(0, $service->getAssistanceConfirmations());
        $this->assertNull($confirmation->getService());
    }

    /**
     * Tests adding and removing volunteer services.
     */
    public function testVolunteerServices(): void
    {
        $service = new Service();
        $volunteerService = new VolunteerService();

        $this->assertCount(0, $service->getVolunteerServices());
        $service->addVolunteerService($volunteerService);
        $this->assertCount(1, $service->getVolunteerServices());
        $this->assertSame($service, $volunteerService->getService());

        $service->removeVolunteerService($volunteerService);
        $this->assertCount(0, $service->getVolunteerServices());
        $this->assertNull($volunteerService->getService());
    }
}
