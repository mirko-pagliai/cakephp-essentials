<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Model\Behavior;

use Cake\Database\Schema\TableSchema;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Essentials\Model\Behavior\PlainTextBehavior;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * PlainTextBehaviorTest.
 */
#[CoversClass(PlainTextBehavior::class)]
class PlainTextBehaviorTest extends TestCase
{
    protected PlainTextBehavior $PlainTextBehavior;

    protected Table $Table;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->Table = new class extends Table {
            public function getSchema(): TableSchemaInterface
            {
                return new TableSchema('MyTable', ['text' => ['type' => 'string'], 'plain_text' => ['type' => 'string']]);
            }
        };

        $this->PlainTextBehavior = new PlainTextBehavior($this->Table);
    }

    #[Test]
    public function testBeforeSave(): void
    {
        $Entity = $this->Table->newEmptyEntity();

        /** @var \Cake\Essentials\Model\Behavior\PlainTextBehavior&\Mockery\MockInterface $PlainTextBehavior */
        $PlainTextBehavior = Mockery::mock(PlainTextBehavior::class . '[setTargetField]', [$this->Table]);
        $PlainTextBehavior
            ->shouldReceive('setTargetField')
            ->with($Entity)
            ->once();

        $this->Table->behaviors()->set('PlainTextBehavior', $PlainTextBehavior);

        $this->Table->dispatchEvent('Model.beforeSave', [$Entity]);
    }

    #[Test]
    public function setTargetTest(): void
    {
        $Entity = $this->Table->newEntity(['text' => '<b>My <em>HTML</em> text</b>']);

        $this->PlainTextBehavior->setTargetField($Entity);

        $this->assertSame('My HTML text', $Entity->get('plain_text'));
    }

    #[Test]
    public function testSetTargetFieldOnMissingOriginField(): void
    {
        $this->PlainTextBehavior->setConfig('originField', 'no_existing_origin_field');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing `no_existing_origin_field` column on `' . $this->PlainTextBehavior->table()->getTable() . '` table');
        $this->PlainTextBehavior->setTargetField($this->Table->newEmptyEntity());
    }

    #[Test]
    public function testSetTargetFieldOnMissingTargetField(): void
    {
        $this->PlainTextBehavior->setConfig('targetField', 'no_existing_target_field');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing `no_existing_target_field` column on `' . $this->PlainTextBehavior->table()->getTable() . '` table');
        $this->PlainTextBehavior->setTargetField($this->Table->newEmptyEntity());
    }
}
