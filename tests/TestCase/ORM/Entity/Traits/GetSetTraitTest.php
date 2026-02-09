<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\ORM\Entity\Traits;

use App\Model\Entity\EntityWithSomeVirtualFields;
use BadMethodCallException;
use Cake\Datasource\Exception\MissingPropertyException;
use Cake\Essentials\ORM\Entity\Traits\GetSetTrait;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * GetSetTraitTest.
 */
#[CoversTrait(GetSetTrait::class)]
class GetSetTraitTest extends TestCase
{
    protected EntityWithSomeVirtualFields $Entity;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Entity = new EntityWithSomeVirtualFields();
    }

    /**
     * @param non-empty-string $expectedMethod
     * @param non-empty-string $methodToCall
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    #[TestWith(['getOrFail', 'getExampleProperty'])]
    #[TestWith(['isOrFail', 'isExampleProperty'])]
    public function testCallMagicMethod(string $expectedMethod, string $methodToCall): void
    {
        $Entity = $this->createPartialMock(EntityWithSomeVirtualFields::class, ['getOrFail', 'isOrFail']);
        $Entity
            ->expects($this->once())
            ->method($expectedMethod)
            ->with('example_property');

        $Entity->{$methodToCall}();
    }

    #[Test]
    public function testMagicCallGetMethodsWithNoExistingProperty(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method `' . $this->Entity::class . '::noExistingMethod()` does not exist. `get{PropertyName}()`/`is{PropertyName}()` expected.');
        // @phpstan-ignore-next-line
        $this->Entity->noExistingMethod();
    }

    #[Test]
    #[TestWith(['a value'])]
    #[TestWith([false])]
    #[TestWith([''])]
    #[TestWith([0])]
    #[TestWith([0.0])]
    #[TestWith([[]])]
    public function testGetOrFail(mixed $value): void
    {
        $this->Entity->set('example_property', $value);
        $this->assertSame($value, $this->Entity->getOrFail('example_property'));
    }

    #[Test]
    #[TestWith(['null_property'])]
    #[TestWith(['no_existing_property'])]
    public function testGetOrFailWithNullableOrNoExistingProperty(string $nullableOrNoExistingProperty): void
    {
        $this->Entity->set('null_property');

        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Property `' . $nullableOrNoExistingProperty . '` does not exist for the entity `' . $this->Entity::class . '`');
        $this->Entity->getOrFail($nullableOrNoExistingProperty);
    }

    #[Test]
    public function testGetOrFailOnVirtualField(): void
    {
        $this->assertNotEmpty($this->Entity->getOrFail('stringable_virtual_field'));
    }

    #[Test]
    public function testGetOrFailOnNullableVirtualField(): void
    {
        //First check that the virtual property exists and is `null`
        $this->assertTrue($this->Entity->has('nullable_virtual_field'));
        $this->assertNull($this->Entity->get('nullable_virtual_field'));

        $this->expectException(MissingPropertyException::class);
        $this->expectExceptionMessage('Property `nullable_virtual_field` does not exist for the entity `' . $this->Entity::class . '`');
        $this->Entity->getOrFail('nullable_virtual_field');
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    #[Test]
    public function testIsOrFail(): void
    {
        $Entity = $this->createPartialMock(EntityWithSomeVirtualFields::class, ['getOrFail']);
        $Entity
            ->expects($this->once())
            ->method('getOrFail')
            ->with('example_property');

        $Entity->isOrFail('example_property');
    }

    #[Test]
    #[TestWith([true, true])]
    #[TestWith([false, false])]
    #[TestWith([true, 'a string'])]
    public function testIsOrFailWithSomeValues(bool $expectedResult, mixed $value): void
    {
        $this->Entity->set('example_property', $value);
        $this->assertSame($expectedResult, $this->Entity->isOrFail('example_property'));
    }
}
