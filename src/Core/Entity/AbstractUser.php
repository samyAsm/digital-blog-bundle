<?php
/**
 * Date: 15/03/21
 * Time: 11:02
 */

namespace Dhi\BlogBundle\Core\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractUser extends AbstractPerson implements UserInterface
{

    const IDENTITY_TYPES = [
        'CNI' => 'CNI',
        'PASSPORT' => 'PASSPORT',
    ];

    const GENDERS_TYPES = [
        'F' => 'F',
        'M' => 'M',
    ];

    /**
     * Returns the roles granted to the user.
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

}