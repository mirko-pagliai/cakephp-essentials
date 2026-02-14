<?php
declare(strict_types=1);

namespace Cake\Essentials\Validation;

use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\Validation\Validator as CakeValidator;
use Closure;
use function Cake\Essentials\toDateTime;
use function Cake\I18n\__d as __d;

/**
 * Advanced validation class for data input.
 *
 * Extends CakePHP's standard validation by adding specialized rules for string and temporal data validation.
 *
 * This class enables the easy application of recurring constraints for input fields, such as enforcing the presence
 * of special characters, numbers, uppercase/lowercase letters, the exclusion of reserved words, or performing
 * date/time checks relative now or to reference values.
 *
 * It is suitable whenever additional rules beyond the built-in framework validations are required, simplifying the
 * management of custom checks and ensuring greater reliability and consistency of validated data.
 *
 * The rules are designed for common business requirements, especially for validating passwords, personal data,
 * birthdays, and any other fields needing complex, both static and dynamic, constraints.
 *
 * You can apply multiple rules to the same instance, facilitating the construction of rich and reusable
 * validation workflows.
 *
 * @see \Cake\Validation\Validator
 */
class Validator extends CakeValidator
{
    protected const array RESERVED_WORDS = [
        '1234', '5678', '7890', '0000', 'admin', 'manager', 'root', 'supervisor', 'moderator', 'mail', 'pwd', 'password',
        'passwd', 'juventus', 'napoli', 'milan', 'inter',
    ];

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
            'message' => $message ?: __d('cake/essentials', 'It must contain at least one capital character'),
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
            'message' => $message ?: __d('cake/essentials', 'It must contain at least one numeric digit'),
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
            'message' => $message ?: __d('cake/essentials', 'It must contain at least one lowercase character'),
        ]);

        return $this->add(field: $field, name: 'containsLowercaseLetter', rule: $extra + ['rule' => ['custom', '/[a-z]/']]);
    }

    /**
     * Validates that the specified field contains a valid date or datetime value.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function dateOrDateTime(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It must be a valid date or datetime'),
        ]);

        /** @var class-string<\Cake\Validation\Validation> $defaultProvider */
        $defaultProvider = $this->getProvider('default');

        return $this->add(field: $field, name: 'dateOrDateTime', rule: $extra + [
            'rule' => fn(string $value): bool => $defaultProvider::date($value) || $defaultProvider::dateTime($value),
        ]);
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
            'message' => $message ?: __d('cake/essentials', 'It must begin with a capital letter'),
        ]);

        return $this->add(field: $field, name: 'firstLetterCapitalized', rule: $extra + ['rule' => ['custom', '/^\p{Lu}/u']]);
    }

    /**
     * Adds a validation rule that ensures the datetime/date value of the given field is greater than the datetime/date
     *  value of another specified field.
     *
     * @param string $field The name of the field to which the rule should be applied.
     * @param string $secondField The name of the field containing the datetime value to compare against.
     * @param string|null $message Optional custom error message to be used if the validation rule is violated.
     * @param \Closure|string|null $when Optional condition under which the rule is applied.
     * @return self Returns the current instance for method chaining.
     */
    public function greaterThanDateTimeField(
        string $field,
        string $secondField,
        ?string $message = null,
        Closure|string|null $when = null,
    ): self {
        $extra = array_filter(['on' => $when]);

        return $this->add(
            field: $field,
            name: 'greaterThanDateTimeField',
            rule: $extra + [
                'rule' => function (DateTime|Date|string $dateTime, array $context) use ($secondField, $message): true|string {
                    if (empty($context['data'][$secondField])) {
                        return true;
                    }

                    $secondDateTime = toDateTime($context['data'][$secondField]);
                    if (toDateTime($dateTime)->greaterThan($secondDateTime)) {
                        return true;
                    }

                    return $message ?: __d(
                        'cake/essentials',
                        'It must be greater than `{0}`',
                        $secondDateTime->i18nFormat(),
                    );
                },
            ],
        );
    }

    /**
     * Adds a validation rule that ensures the given field contains a datetime/date value greater than the specified
     *  comparison datetime/date value.
     *
     * @param string $field The name of the field to which the rule should be applied.
     * @param \Cake\I18n\DateTime|\Cake\I18n\Date $comparisonValue The datetime/date value to compare against.
     * @param string|null $message Optional custom error message to be used if the validation rule is violated.
     * @param \Closure|string|null $when Optional condition under which the rule is applied.
     * @return self Returns the current instance for method chaining.
     */
    public function greaterThanDateTime(
        string $field,
        DateTime|Date $comparisonValue,
        ?string $message = null,
        Closure|string|null $when = null,
    ): self {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d(
                'cake/essentials',
                'It must be greater than `{0}`',
                $comparisonValue->i18nFormat(),
            ),
        ]);

        return $this->add(field: $field, name: 'greaterThanDateTime', rule: $extra + [
            'rule' => fn(DateTime|Date|string $dateTime): bool => toDateTime($dateTime)->greaterThan(toDateTime($comparisonValue)),
        ]);
    }

    /**
     * Adds a validation rule that ensures the given field contains a datetime/date value greater or equal to the
     * specified comparison datetime/date value.
     *
     * @param string $field The name of the field to validate.
     * @param \Cake\I18n\DateTime|\Cake\I18n\Date $comparisonValue The datetime/date value to compare against.
     * @param string|null $message An optional custom error message to return when the validation fails.
     * @param \Closure|string|null $when A condition determining when this validation rule should apply.
     * @return self Returns the current instance with the added validation rule.
     */
    public function greaterThanOrEqualsDateTime(
        string $field,
        DateTime|Date $comparisonValue,
        ?string $message = null,
        Closure|string|null $when = null,
    ): self {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d(
                'cake/essentials',
                'It must be greater than or equal to `{0}`',
                $comparisonValue->i18nFormat(),
            ),
        ]);

        return $this->add(field: $field, name: 'greaterThanOrEqualsDateTime', rule: $extra + [
            'rule' => fn(DateTime|Date|string $dateTime): bool => toDateTime($dateTime)->greaterThanOrEquals(toDateTime($comparisonValue)),
        ]);
    }

    /**
     * Adds a validation rule that ensures the given field does not contain spaces at the beginning or the end of its value.
     *
     * @param string $field The name of the field to which the rule should be applied.
     * @param string|null $message Optional custom error message to be used if the validation rule is violated.
     * @param \Closure|string|null $when Optional condition under which the rule is applied.
     * @return self Returns the current instance for method chaining.
     */
    public function noStartOrEndSpace(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d(
                'cake/essentials',
                'It cannot contain spaces at the beginning or at the end',
            ),
        ]);

        return $this->add(field: $field, name: 'noStartOrEndSpace', rule: $extra + [
            'rule' => fn(string $value): bool => $value === trim($value),
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
    public function notContainsReservedWords(
        string $field,
        ?string $message = null,
        Closure|string|null $when = null,
    ): self {
        $extra = array_filter(['on' => $when]);

        return $this->add(field: $field, name: 'notContainsReservedWords', rule: $extra + [
            'rule' => function (string $value) use ($message): bool|string {
                $result = preg_match(
                    pattern: '/(' . implode('|', array_map('preg_quote', self::RESERVED_WORDS)) . ')/i',
                    subject: $value,
                    matches: $matches,
                );

                if ($result && isset($matches[0])) {
                    return $message ?: __d(
                        'cake/essentials',
                        'It cannot contain the reserved word `{0}`',
                        $matches[0],
                    );
                }

                return !$result;
            },
        ]);
    }

    /**
     * Adds a validation rule to ensure that the value of the specified field is not a future datetime/date.
     *
     * @param string $field The name of the field to validate.
     * @param string|null $message An optional custom error message to return when the validation fails.
     * @param \Closure|string|null $when A condition determining when this validation rule should apply.
     * @return self Returns the current instance with the added validation rule.
     */
    public function notFutureDatetime(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It cannot be a future datetime'),
        ]);

        return $this->add(field: $field, name: 'notFutureDatetime', rule: $extra + [
            'rule' => fn(DateTime|Date|string $dateTime): bool => !toDateTime($dateTime)->isFuture(),
        ]);
    }

    /**
     * Adds a validation rule to ensure that the value of the specified field is not a past datetime/date.
     *
     * @param string $field The name of the field to which the rule should be applied.
     * @param string|null $message Optional custom error message to be used if the validation rule is violated.
     * @param \Closure|string|null $when Optional condition under which the rule is applied.
     * @return self Returns the current instance for method chaining.
     */
    public function notPastDatetime(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It cannot be a past datetime'),
        ]);

        return $this->add(field: $field, name: 'notPastDatetime', rule: $extra + [
            'rule' => fn(DateTime|Date|string $dateTime): bool => !toDateTime($dateTime)->isPast(),
        ]);
    }

    /**
     * Validates that the specified field contains a valid person name.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function personName(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It must be a valid person name'),
        ]);

        return $this
            ->setStopOnFailure()
            ->noStartOrEndSpace(field: $field, message: $message, when: $when)
            ->minLength(
                field: $field,
                min: 2,
                message: $message ?: __d('cake/essentials', 'It must be at least {0} characters long', 2),
                when: $when,
            )
            ->maxLength(
                field: $field,
                max: 40,
                message: $message ?: __d('cake/essentials', 'It must be at a maximum of {0} characters long', 40),
                when: $when,
            )
            ->firstLetterCapitalized(field: $field, message: $message, when: $when)
            ->add(field: $field, name: 'personName', rule: $extra + [
                /** @see https://chatgpt.com/share/685ace35-de4c-800c-8788-07b4b36764bc */
                'rule' => ['custom', '/^(?=(?:.*[A-ZÀÈÉÌÒÙa-zàèéìòù]){2,})[A-ZÀÈÉÌÒÙ][a-zàèéìòù]*(?:[ \'\-][A-ZÀÈÉÌÒÙ][a-zàèéìòù]*)*$/u'],
            ]);
    }

    /**
     * Adds a validation rule to ensure the value of a field is a valid slug (at least 3 characters).
     *
     * @param string $field The name of the field to validate.
     * @param string|null $message An optional custom error message to return when the validation fails.
     * @param \Closure|string|null $when A condition determining when this validation rule should apply.
     * @return self Returns the current instance with the added validation rule.
     */
    public function slug(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It must be a valid slug'),
        ]);

        return $this->add(field: $field, name: 'slug', rule: $extra + ['rule' => ['custom', '/^[a-z][a-z\d\-]{2,}$/']]);
    }

    /**
     * Validates that the specified field is a valid title.
     *
     * @param string $field The name of the field to be validated.
     * @param string|null $message An optional custom validation failure message.
     * @param \Closure|string|null $when Conditions specifying when this rule should be applied.
     * @return self Returns the current instance with the added validation rule.
     */
    public function title(string $field, ?string $message = null, Closure|string|null $when = null): self
    {
        $extra = array_filter([
            'on' => $when,
            'message' => $message ?: __d('cake/essentials', 'It must be a valid title'),
        ]);

        return $this
            ->setStopOnFailure()
            ->noStartOrEndSpace(field: $field, message: $message, when: $when)
            ->minLength(
                field: $field,
                min: 3,
                message: $message ?: __d('cake/essentials', 'It must be at least {0} characters long', 3),
                when: $when,
            )
            ->firstLetterCapitalized(field: $field, message: $message, when: $when)
            ->add(field: $field, name: 'title', rule: $extra + [
                /** @see https://chatgpt.com/share/685b2675-5178-800c-9106-649bdd9079ac */
                'rule' => ['custom', '/^\s*([\p{L}\p{N}\/, \'()\-:](?:[\p{L}\p{N}\/, \'()\-: ]*[\p{L}\p{N}\/, \'()\-:])?)\s*$/u'],
            ]);
    }

    /**
     * Validates that the specified field complies with password strength requirements.
     *
     * Ensures the password meets the following criteria:
     * - At least 12 characters in length.
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
            ->minLength(field: $field, min: 12, message: $message, when: $when)
            ->containsDigit(field: $field, message: $message, when: $when)
            ->containsCapitalLetter(field: $field, message: $message, when: $when)
            ->containsLowercaseLetter(field: $field, message: $message, when: $when)
            ->notAlphaNumeric(
                field: $field,
                message: $message ?: __d(
                    'cake/essentials',
                    'It must contain at least one special character',
                ),
                when: $when,
            )
            ->notContainsReservedWords(field: $field, message: $message, when: $when);
    }
}
