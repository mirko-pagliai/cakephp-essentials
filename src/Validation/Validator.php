<?php
declare(strict_types=1);

namespace Cake\Essentials\Validation;

use Cake\Validation\Validator as CakeValidator;
use Closure;
use function Cake\I18n\__d as __d;

/**
 * Class Validator
 *
 * Extends the CakeValidator class by adding custom validation rules to check
 * the presence of specific character patterns such as capital letters, digits,
 * lowercase letters, or ensuring text starts with a capital letter.
 *
 * {@inheritDoc}
 */
class Validator extends CakeValidator
{
    /**
     * Validates that the specified field contains at least one capital letter.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function containsCapitalLetter(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'Must contain at least one capital character'),
        ]);

        return $this->add(field: $field, name: 'containsCapitalLetter', rule: $extra + ['rule' => ['custom', '/[A-Z]/']]);
    }

    /**
     * Validates that the specified field contains at least one numeric digit.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function containsDigit(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'Must contain at least one numeric digit'),
        ]);

        return $this->add(field: $field, name: 'containsDigit', rule: $extra + ['rule' => ['custom', '/\d/']]);
    }

    /**
     * Validates that the specified field contains at least one lowercase letter.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function containsLowercaseLetter(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'Must contain at least one lowercase character'),
        ]);

        return $this->add(field: $field, name: 'containsLowercaseLetter', rule: $extra + ['rule' => ['custom', '/[a-z]/']]);
    }

    /**
     * Validates that the specified field begins with a capital letter.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function firstLetterCapitalized(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'Has to begin with a capital letter'),
        ]);

        return $this->add(field: $field, name: 'firstLetterCapitalized', rule: $extra + [
            'rule' => fn($value): bool => is_string($value) && ctype_upper($value[0] ?? ''),
        ]);
    }

    /**
     * Validates that the specified field does not contain any reserved words.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function notContainsReservedWords(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'Cannot contain any reserved words'),
        ]);

        return $this->add(field: $field, name: 'notContainReservedWords', rule: $extra + [
            'rule' => ['custom', '/^((?!admin|manager|root|supervisor|moderator|mail|pwd|password|passwd).)+$/i'],
        ]);
    }

    /**
     * Validates that the specified field complies with password strength requirements.
     * Ensures the password meets the following criteria:
     * - At least 8 characters in length.
     * - Contains at least one digit.
     * - Contains at least one uppercase letter.
     * - Contains at least one lowercase letter.
     * - Contains at least one special character.
     * - Does not include reserved words.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the combined password validation rules.
     */
    public function validPassword(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        return $this
            ->minLength(field: $field, min: 8, message: $message, when: $when)
            ->containsDigit(field: $field, message: $message, when: $when)
            ->containsCapitalLetter(field: $field, message: $message, when: $when)
            ->containsLowercaseLetter(field: $field, message: $message, when: $when)
            ->notAlphaNumeric(
                field: $field,
                message: $message ?: __d('cake/essentials', 'Must contain at least one special character'),
                when: $when,
            )
            ->notContainsReservedWords(field: $field, message: $message, when: $when);
    }
}
