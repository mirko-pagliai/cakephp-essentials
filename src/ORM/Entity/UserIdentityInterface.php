<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity;

use Authentication\IdentityInterface as AuthenticationIdentityInterface;
use Authorization\AuthorizationServiceInterface;
use Authorization\IdentityInterface as AuthorizationIdentityInterface;

/**
 * This interface can be implemented by a `User` entity that you want to use as identity.
 *
 * The interface, since it in turn extends `Authentication\IdentityInterface` and `Authorization\IdentityInterface`
 *  interfaces, must provide all the methods that they need to implement.
 *
 * The fastest and most effective strategy to implement these methods is to use the `UserIdentityTrait` trait.
 *
 * @see \Cake\Essentials\ORM\Entity\Traits\UserIdentityTrait
 */
interface UserIdentityInterface extends AuthenticationIdentityInterface, AuthorizationIdentityInterface, EntityWithGetSetInterface
{
    /**
     * Setter to be used by the middleware.
     *
     * @param \Authorization\AuthorizationServiceInterface $service
     * @return self
     */
    public function setAuthorization(AuthorizationServiceInterface $service): self;
}
