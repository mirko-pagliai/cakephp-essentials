<?php
declare(strict_types=1);

namespace Cake\Essentials\TestSuite\Traits;

use Cake\Datasource\EntityInterface;
use Cake\Essentials\ORM\Entity\UserIdentityInterface;

/**
 * Trait AssertPolicyTrait
 *
 * Provides methods to assert the results of policy method evaluations.
 */
trait AssertPolicyTrait
{
    /**
     * Asserts the result of a policy method execution against the expected result.
     *
     * Example:
     * ```
     * $this->assertPolicyResult(true, 'canAdd', $User, new Article());
     * ```
     * This asserts that the method `ArticlePolicy::canAdd()`, for the `$User` identity, returns `true`.
     *
     * @param bool $expectedResult The expected boolean result of the policy method.
     * @param string $method The method name to call on the policy object.
     * @param \Cake\Essentials\ORM\Entity\UserIdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface $Entity Entity object passed to the policy method
     *
     * @return void The method does not return a value but performs an assertion.
     */
    public function assertPolicyResult(
        bool $expectedResult,
        string $method,
        UserIdentityInterface&EntityInterface $Identity,
        EntityInterface $Entity,
    ): void {
        $result = $Identity->can($method, $Entity);

        $this->assertSame(
            $expectedResult,
            $result,
            sprintf(
                '`%s()` method has returned `%s`',
                $method,
                $result ? 'true' : 'false'
            )
        );
    }

    /**
     * Asserts that the policy result is `false` based on the provided parameters.
     *
     * This method is an alias for `assertPolicyResult()`, where the first argument `$expectedResult` is assumed to be `false`.
     *
     * Example:
     * ```
     * $this->assertPolicyResultFalse('canAdd', $User, new Article());
     * ```
     * This asserts that the method `ArticlePolicy::canAdd()`, for the `$User` identity, returns `false`.
     *
     * @param string $method The method name to call on the policy object.
     * @param \Cake\Essentials\ORM\Entity\UserIdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface $Entity Entity object passed to the policy method.
     *
     * @return void
     */
    public function assertPolicyResultFalse(
        string $method,
        UserIdentityInterface&EntityInterface $Identity,
        EntityInterface $Entity,
    ): void {
        $this->assertPolicyResult(expectedResult: false, method: $method, Identity: $Identity, Entity: $Entity);
    }

    /**
     * Asserts that the policy result is `true` based on the provided parameters.
     *
     * This method is an alias for `assertPolicyResult()`, where the first argument `$expectedResult` is assumed to be `true`.
     *
     * Example:
     * ```
     * $this->assertPolicyResultTrue('canAdd', $User, new Article());
     * ```
     * This asserts that the method `ArticlePolicy::canAdd()`, for the `$User` identity, returns `true`.
     *
     * @param string $method The method name to call on the policy object.
     * @param \Cake\Essentials\ORM\Entity\UserIdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface $Entity Entity object passed to the policy method.
     *
     * @return void
     */
    public function assertPolicyResultTrue(
        string $method,
        UserIdentityInterface&EntityInterface $Identity,
        EntityInterface $Entity,
    ): void {
        $this->assertPolicyResult(expectedResult: true, method: $method, Identity: $Identity, Entity: $Entity);
    }
}
