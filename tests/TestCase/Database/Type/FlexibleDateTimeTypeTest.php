<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Database\Type;

use Cake\Essentials\Database\Type\FlexibleDateTimeType;
use Cake\TestSuite\TestCase;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

/**
 * FlexibleDateTimeTypeTest.
 */
#[CoversClass(FlexibleDateTimeType::class)]
class FlexibleDateTimeTypeTest extends TestCase
{
    protected FlexibleDateTimeType $type;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->type = new FlexibleDateTimeType();
    }

    /**
     * @link \Cake\Essentials\Database\Type\FlexibleDateTimeType::marshal()
     */
    #[Test]
    #[TestWith([null])]
    #[TestWith([''])]
    #[TestWith(['invalid-date'])]
    public function testMarshalReturnsNullForNullOrEmptyStringOrInvalidString(?string $value): void
    {
        $result = $this->type->marshal($value);

        $this->assertNull($result);
    }

    /**
     * @link \Cake\Essentials\Database\Type\FlexibleDateTimeType::marshal()
     */
    public function testMarshalAcceptsIsoDateOnly(): void
    {
        $result = $this->type->marshal('2026-02-11');

        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2026-02-11 00:00:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * @link \Cake\Essentials\Database\Type\FlexibleDateTimeType::marshal()
     */
    public function testMarshalAcceptsIsoDateTimeWithoutSeconds(): void
    {
        $result = $this->type->marshal('2026-02-11T14:30');

        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2026-02-11 14:30:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * @link \Cake\Essentials\Database\Type\FlexibleDateTimeType::marshal()
     */
    public function testMarshalAcceptsIsoDateTimeWithSeconds(): void
    {
        $result = $this->type->marshal('2026-02-11 14:30:45');

        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame('2026-02-11 14:30:45', $result->format('Y-m-d H:i:s'));
    }

    /**
     * @link \Cake\Essentials\Database\Type\FlexibleDateTimeType::marshal()
     */
    public function testMarshalReturnsSameInstanceForDateTimeInterface(): void
    {
        $original = new DateTimeImmutable('2026-02-11 10:00:00');

        $result = $this->type->marshal($original);

        $this->assertInstanceOf(DateTimeInterface::class, $result);
        $this->assertSame(
            $original->format('Y-m-d H:i:s'),
            $result->format('Y-m-d H:i:s'),
        );
    }
}
