<?php
declare(strict_types=1);

namespace Cake\Essentials\ORM\Entity\Traits;

use Authorization\AuthorizationServiceInterface;
use Authorization\Policy\ResultInterface;

/**
 * This trait allows you to use your `User` class as the identity, providing all the necessary methods and so you
 *  can quickly use authentication and authorization plugins.
 *
 * Remember to update your middleware setup:
 * ```
 * $middlewareQueue->add(new AuthorizationMiddleware($this, [
 *    'identityDecorator' => function (AuthorizationService $Auth, UserIdentityInterface $User): UserIdentityInterface {
 *       return $User->setAuthorization($Auth);
 *    },
 * ]));
 * ```
 *
 * @phpstan-require-implements \Cake\Essentials\ORM\Entity\UserIdentityInterface
 *
 * @see https://book.cakephp.org/authentication/3/en/identity-object.html#implementing-the-identityinterface-on-your-user-class
 * @see https://book.cakephp.org/authorization/3/en/middleware.html#using-your-user-class-as-the-identity
 */
trait UserIdentityTrait
{
    /**
     * @var \Authorization\AuthorizationServiceInterface $Authorization
     */
    public protected(set) AuthorizationServiceInterface $Authorization;

    /**
     * Checks whether the provided identity can perform an action on a resource.
     *
     * @param string $action The action/operation being performed
     * @param mixed $resource The resource being operated on
     * @return bool
     */
    public function can(string $action, mixed $resource): bool
    {
        return $this->Authorization->can($this, $action, $resource);
    }

    /**
     * Checks whether the provided identity can perform an action on a resource.
     *
     * @param string $action The action/operation being performed
     * @param mixed $resource The resource being operated on
     * @return \Authorization\Policy\ResultInterface
     */
    public function canResult(string $action, mixed $resource): ResultInterface
    {
        return $this->Authorization->canResult($this, $action, $resource);
    }

    /**
     * Applies authorization scope conditions/restrictions.
     *
     * This method is intended for applying authorization to objects that are then used to access authorized collections
     *  of objects. The typical use case for scopes is restricting a query to only return records visible to the current
     *  user.
     *
     * @param string $action The action/operation being performed
     * @param mixed $resource The resource being operated on
     * @param mixed $optionalArgs Multiple additional arguments which are passed to the scope
     * @return mixed The modified resource
     */
    public function applyScope(string $action, mixed $resource, mixed ...$optionalArgs): mixed
    {
        return $this->Authorization->applyScope($this, $action, $resource, ...$optionalArgs);
    }

    /**
     * Get the primary `id` field for the identity.
     *
     * @return int
     */
    public function getIdentifier(): int
    {
        /** @var int */
        return $this->getOrFail('id');
    }

    /**
     * Get the decorated identity.
     *
     * @return self
     */
    public function getOriginalData(): self
    {
        return $this;
    }

    /**
     * Setter to be used by the middleware.
     *
     * @param \Authorization\AuthorizationServiceInterface $service
     * @return self
     */
    public function setAuthorization(AuthorizationServiceInterface $service): self
    {
        $this->Authorization = $service;

        return $this;
    }
}
