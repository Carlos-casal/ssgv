<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\User;

/**
 * Represents the interface that all user classes must implement.
 *
 * This interface is useful because the authentication layer can deal with
 * the object through its lifecycle, assigning roles and so on.
 *
 * Regardless of how your users are loaded or where they come from (a database,
 * configuration, web service, etc.), you will have a class that implements
 * this interface. Objects that implement this interface are created and
 * loaded by different objects that implement UserProviderInterface.
 *
 * The __serialize/__unserialize() magic methods can be implemented on the user
 * class to prevent sensitive credentials from being put in the session storage.
 *
 * @see UserProviderInterface
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface UserInterface
{
    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @deprecated since Symfony 7.3, erase credentials using the "__serialize()" method instead
     */
    public function eraseCredentials(): void;

    /**
     * Returns the identifier for this user (e.g. username or email address).
     *
     * @return non-empty-string
     */
    public function getUserIdentifier(): string;
}
