<?php

namespace App\Tests\Entity;

use App\Entity\Fichaje;
use App\Entity\VolunteerService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Fichaje entity.
 * @group Entity
 */
class FichajeTest extends TestCase
{
    /**
     * Tests that a new Fichaje object can be instantiated and its default values are correct.
     */
    public function testNewFichaje(): void
    {
        $fichaje = new Fichaje();
        $this->assertInstanceOf(Fichaje::class, $fichaje);
        $this->assertNull($fichaje->getId());
        $this->assertNull($fichaje->getStartTime());
        $this->assertNull($fichaje->getEndTime());
        $this->assertNull($fichaje->getNotes());
    }

    /**
     * Tests the getter and setter for the startTime property.
     */
    public function testGetSetStartTime(): void
    {
        $fichaje = new Fichaje();
        $startTime = new \DateTime();
        $fichaje->setStartTime($startTime);

        $this->assertSame($startTime, $fichaje->getStartTime());
    }

    /**
     * Tests the getter and setter for the endTime property.
     */
    public function testGetSetEndTime(): void
    {
        $fichaje = new Fichaje();
        $endTime = new \DateTime();
        $fichaje->setEndTime($endTime);

        $this->assertSame($endTime, $fichaje->getEndTime());
    }

    /**
     * Tests the relationship with VolunteerService.
     */
    public function testGetSetVolunteerService(): void
    {
        $fichaje = new Fichaje();
        $volunteerService = new VolunteerService();

        $fichaje->setVolunteerService($volunteerService);

        $this->assertSame($volunteerService, $fichaje->getVolunteerService());
    }
}
