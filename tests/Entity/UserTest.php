<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Volunteer;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the User entity.
 * @group Entity
 */
class UserTest extends TestCase
{
    /**
     * Tests that a new User object can be instantiated and its default values are correct.
     */
    public function testNewUser(): void
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
        $this->assertNull($user->getId());
        $this->assertNull($user->getEmail());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertNull($user->getVolunteer());
    }

    /**
     * Tests the getter and setter for the email property.
     */
    public function testGetSetEmail(): void
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);

        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    /**
     * Tests the role management logic.
     */
    public function testRoles(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        // ROLE_USER should always be present
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());

        // Test that roles are unique
        $user->setRoles(['ROLE_VOLUNTEER', 'ROLE_VOLUNTEER']);
        $this->assertCount(2, $user->getRoles()); // ROLE_VOLUNTEER and ROLE_USER
    }

    /**
     * Tests the getter and setter for the password property.
     */
    public function testGetSetPassword(): void
    {
        $user = new User();
        $password = 'a_very_secure_password';
        $user->setPassword($password);

        $this->assertSame($password, $user->getPassword());
    }

    /**
     * Tests the bidirectional relationship between User and Volunteer.
     */
    public function testGetSetVolunteer(): void
    {
        $user = new User();
        $volunteer = new Volunteer();

        $user->setVolunteer($volunteer);

        $this->assertSame($volunteer, $user->getVolunteer());
        // Check if the back-reference was set correctly
        $this->assertSame($user, $volunteer->getUser());
    }

    /**
     * Tests that setting the same volunteer again does not cause issues.
     */
    public function testSetSameVolunteer(): void
    {
        $user = new User();
        $volunteer = new Volunteer();

        $user->setVolunteer($volunteer);
        $user->setVolunteer($volunteer); // Set the same volunteer again

        $this->assertSame($volunteer, $user->getVolunteer());
        $this->assertSame($user, $volunteer->getUser());
    }

    /**
     * Tests that eraseCredentials does not throw an error.
     */
    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password');

        // The method is expected to be empty, so we just check it doesn't throw an error.
        $user->eraseCredentials();
        $this->assertTrue(true);
    }
}