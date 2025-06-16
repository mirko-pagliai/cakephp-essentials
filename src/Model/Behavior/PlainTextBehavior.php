<?php
declare(strict_types=1);

namespace Cake\Essentials\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Behavior;
use InvalidArgumentException;

/**
 * PlainTextBehavior is used to sanitize the content of a specified field in the entity,
 * and save a "plain text" version of it in another field before the entity is persisted.
 *
 * In your Table's `initialize()` method:
 * ```
 * $this->addBehavior('Cake/Essentials.PlainText');
 * ```
 *
 * Before saving an entity, it automatically sets a field containing a "flat" text (by default `plain_text`) from the
 *  contents of a text field (which can include html, by default `text`).
 *
 * For example, if the `text` field is `<b>My text</b>`, it automatically sets the `plain_text` field to `My text`.
 */
class PlainTextBehavior extends Behavior
{
    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'originField' => 'text',
        'targetField' => 'plain_text',
    ];

    /**
     * The `Model.beforeSave` event is fired before each entity is saved. Stopping this event will abort the save operation.
     *
     * When the event is stopped the result of the event will be returned.
     *
     * @param \Cake\Event\EventInterface $Event
     * @param \Cake\Datasource\EntityInterface $Entity
     * @return void
     */
    public function beforeSave(EventInterface $Event, EntityInterface $Entity): void
    {
        $this->setTargetField($Entity);
    }

    /**
     * Sets the target field for the entity by copying and sanitizing the value from the origin field.
     *
     * This method retrieves the configuration for the origin and target fields, validates their
     * existence within the table schema, and transfers a sanitized value from the origin field
     * to the corresponding target field within the entity.
     *
     * @param \Cake\Datasource\EntityInterface $Entity The entity instance to set the target field for.
     * @return void
     * @throws \InvalidArgumentException If the required columns (originField or targetField) are missing in the table schema.
     */
    public function setTargetField(EntityInterface $Entity): void
    {
        $TableSchema = $this->table()->getSchema();

        /** @var string $originField */
        $originField = $this->getConfigOrFail('originField');
        /** @var string $targetField */
        $targetField = $this->getConfigOrFail('targetField');

        foreach ([$originField, $targetField] as $field) {
            if (!$TableSchema->hasColumn($field)) {
                throw new InvalidArgumentException(sprintf('Missing `%s` column on `%s` table', $field, $this->table()->getTable()));
            }
        }

        if ($Entity->hasValue($originField)) {
            /** @var string $originValue */
            $originValue = $Entity->get($originField);
            $Entity->set($targetField, strip_tags($originValue));
        }
    }
}
