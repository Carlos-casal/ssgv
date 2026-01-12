<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Volunteer;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Volunteer entity.
 * @group Entity
 */
class VolunteerTest extends TestCase
{
    /**
     * Tests that a new Volunteer object can be instantiated and its default values are correct.
     */
    public function testNewVolunteer(): void
    {
        $volunteer = new Volunteer();
        $this->assertInstanceOf(Volunteer::class, $volunteer);
        $this->assertNull($volunteer->getId());
        $this->assertNull($volunteer->getName());
        $this->assertNull($volunteer->getLastName());
        $this->assertNull($volunteer->getPhone());
        $this->assertNull($volunteer->getDni());
        $this->assertNull($volunteer->getDateOfBirth());
        $this->assertNull($volunteer->getStreetType());
        $this->assertNull($volunteer->getAddress());
        $this->assertNull($volunteer->getPostalCode());
        $this->assertNull($volunteer->getProvince());
        $this->assertNull($volunteer->getCity());
        $this->assertNull($volunteer->getContactPerson1());
        $this->assertNull($volunteer->getContactPhone1());
        $this->assertFalse($volunteer->getHasVolunteeredBefore());
        $this->assertEquals(Volunteer::STATUS_ACTIVE, $volunteer->getStatus());
    }

    /**
     * Tests the getter and setter for the name property.
     */
    public function testGetSetName(): void
    {
        $volunteer = new Volunteer();
        $name = 'John';
        $volunteer->setName($name);

        $this->assertSame($name, $volunteer->getName());
    }

    /**
     * Tests the bidirectional relationship between User and Volunteer.
     */
    public function testGetSetUser(): void
    {
        $volunteer = new Volunteer();
        $user = new User();

        $volunteer->setUser($user);

        $this->assertSame($user, $volunteer->getUser());
        // Check if the back-reference was set correctly
        $this->assertSame($volunteer, $user->getVolunteer());
    }

    /**
     * Tests the status management logic.
     */
    public function testStatus(): void
    {
        $volunteer = new Volunteer();
        $this->assertEquals(Volunteer::STATUS_ACTIVE, $volunteer->getStatus());

        $volunteer->setStatus(Volunteer::STATUS_INACTIVE);
        $this->assertEquals(Volunteer::STATUS_INACTIVE, $volunteer->getStatus());
        $this->assertNotNull($volunteer->getStatusChangeDate());
    }
}
