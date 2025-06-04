<?php
declare(strict_types=1);

namespace Cake\Essentials\TestSuite\Traits;

use Authentication\IdentityInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;

/**
 * Trait providing assertion capabilities to validate the results of policy method executions.
 *
 * This trait is typically used in testing scenarios to ensure that policy methods (such as those
 * used for authorization checks) return the expected results when provided with specific input.
 */
trait AssertPolicyTrait
{
    /**
     * Asserts the result of a policy method execution against the expected result.
     *
     * Example:
     * ```
     * $this->assertPolicyResult(true, ArticlePolicy::class, 'canAdd', 'admin');
     * ```
     * This asserts that the method `ArticlePolicy::canAdd()`, for the user group `admin`, returns `true`.
     *
     * @param bool $expectedResult The expected boolean result of the policy method.
     * @param object|string $Policy The policy object or the class name of the policy to execute.
     * @param string $method The method name to call on the policy object.
     * @param \Authentication\IdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface|null $Entity Optional entity object passed to the policy method. Defaults to null.
     *
     * @return void The method does not return a value but performs an assertion.
     */
    public function assertPolicyResult(
        bool $expectedResult,
        object|string $Policy,
        string $method,
        IdentityInterface&EntityInterface $Identity,
        ?EntityInterface $Entity = null,
    ): void {
        if (!is_object($Policy)) {
            $Policy = new $Policy();
        }

        if (!method_exists($Policy, $method)) {
            $this->fail(sprintf('Policy `%s` does not have a `%s()` method', $Policy::class, $method));
        }

        //Runs the policy and gets `$result`
        $result = $Policy->{$method}($Identity, $Entity ?: new Entity());

        $this->assertSame($expectedResult, $result, sprintf(
            '`%s::%s()` method has returned `%s`',
            $Policy::class,
            $method,
            $result ? 'true' : 'false'
        ));
    }

    /**
     * Asserts that the policy result is `false` based on the provided parameters.
     *
     * This method is an alias for `assertPolicyResult()`, where the first argument `$expectedResult` is assumed to be `false`.
     *
     * @param object|string $Policy The policy object or the class name of the policy to execute.
     * @param string $method The method name to call on the policy object.
     * @param \Authentication\IdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface|null $Entity Optional entity object passed to the policy method. Defaults to null.
     *
     * @return void
     */
    public function assertPolicyResultFalse(
        object|string $Policy,
        string $method,
        IdentityInterface&EntityInterface $Identity,
        ?EntityInterface $Entity = null,
    ): void {
        $this->assertPolicyResult(false, $Policy, $method, $Identity, $Entity);
    }

    /**
     * Asserts that the policy result is `true` based on the provided parameters.
     *
     * This method is an alias for `assertPolicyResult()`, where the first argument `$expectedResult` is assumed to be `true`.
     *
     * @param object|string $Policy The policy object or the class name of the policy to execute.
     * @param string $method The method name to call on the policy object.
     * @param \Authentication\IdentityInterface&\Cake\Datasource\EntityInterface $Identity The identity object used when invoking the policy method.
     * @param \Cake\Datasource\EntityInterface|null $Entity Optional entity object passed to the policy method. Defaults to null.
     *
     * @return void
     */
    public function assertPolicyResultTrue(
        object|string $Policy,
        string $method,
        IdentityInterface&EntityInterface $Identity,
        ?EntityInterface $Entity = null,
    ): void {
        $this->assertPolicyResult(true, $Policy, $method, $Identity, $Entity);
    }
}
