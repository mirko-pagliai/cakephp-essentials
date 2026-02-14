<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Validation;

use Cake\Essentials\Validation\Validator;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use function Cake\Essentials\toDateTime;

/**
 * ValidatorTest.
 */
#[CoversClass(Validator::class)]
class ValidatorTest extends TestCase
{
    protected Validator $Validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->Validator = new Validator();
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsCapitalLetter()
     */
    #[Test]
    #[TestWith(['A'])]
    #[TestWith(['Ab3!'])]
    #[TestWith(['bbA'])]
    #[TestWith(['!bbA'])]
    public function testContainsCapitalLetter(string $stringContainsCapitalLetter): void
    {
        $this->Validator->containsCapitalLetter('text');

        $this->assertEmpty($this->Validator->validate(['text' => $stringContainsCapitalLetter]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsCapitalLetter()
     */
    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a capital letter'])]
    public function testContainsCapitalLetterOnError(string $customMessage = ''): void
    {
        $expected = [
            'text' => [
                'containsCapitalLetter' => $customMessage ?: 'It must contain at least one capital character',
            ],
        ];

        $this->Validator->containsCapitalLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'a3!']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsDigit()
     */
    #[Test]
    #[TestWith(['3'])]
    #[TestWith(['Ab3!'])]
    public function testContainsDigit(string $stringContainsDigits): void
    {
        $this->Validator->containsDigit('text');

        $this->assertEmpty($this->Validator->validate(['text' => $stringContainsDigits]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsDigit()
     */
    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a digit'])]
    public function testContainsDigitOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsDigit' => $customMessage ?: 'It must contain at least one numeric digit']];

        $this->Validator->containsDigit('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'Ab!']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsLowercaseLetter()
     */
    #[Test]
    #[TestWith(['a'])]
    #[TestWith(['aB3!'])]
    public function testContainsLowercaseLetter(string $stringContainsLowecaseLetter): void
    {
        $this->Validator->containsLowercaseLetter('text');

        $this->assertEmpty($this->Validator->validate(['text' => $stringContainsLowecaseLetter]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsLowercaseLetter()
     */
    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a lowercase letter'])]
    public function testContainsLowercaseLetterOnError(string $customMessage = ''): void
    {
        $expected = [
            'text' => [
                'containsLowercaseLetter' => $customMessage ?: 'It must contain at least one lowercase character',
            ],
        ];

        $this->Validator->containsLowercaseLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'A3!']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::dateOrDateTime()
     */
    #[Test]
    #[TestWith(['2025-02-26'])]
    #[TestWith(['2025-02-26 13:00'])]
    #[TestWith(['2025-02-26 13:00:00'])]
    public function testDateOrDateTime(string $dateOrDatetime): void
    {
        $this->Validator->dateOrDateTime('my_field');

        $this->assertEmpty($this->Validator->validate(['my_field' => $dateOrDatetime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::dateOrDateTime()
     */
    #[Test]
    #[TestWith([''])]
    #[TestWith(['invalid-string'])]
    #[TestWith(['2025-02-26 13'])]
    #[TestWith(['2025-02-26 13:00:00:00'])]
    #[TestWith(['invalid-string', 'This is not a good date or datetime!'])]
    public function testDateOrDateTimeOnError(string $badDateOrDatetime, string $customMessage = ''): void
    {
        $expected = ['my_field' => ['dateOrDateTime' => $customMessage ?: 'It must be a valid date or datetime']];

        $this->Validator->dateOrDateTime('my_field', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['my_field' => $badDateOrDatetime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::firstLetterCapitalized()
     */
    #[Test]
    #[TestWith(['Àbate'])]
    #[TestWith(['Text'])]
    public function testFirstLetterCapitalized(string $stringWithFirstLetterCapitalized): void
    {
        $this->Validator->firstLetterCapitalized('text');

        $this->assertEmpty($this->Validator->validate(['text' => $stringWithFirstLetterCapitalized]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::firstLetterCapitalized()
     */
    #[Test]
    #[TestWith(['a'])]
    #[TestWith(['à'])]
    #[TestWith(['1'])]
    #[TestWith(['!'])]
    #[TestWith(['!'])]
    #[TestWith(['1', 'You cannot use a bad title'])]
    public function testFirstLetterCapitalizedOnError(string $badText, string $customMessage = ''): void
    {
        $expected = ['text' => ['firstLetterCapitalized' => $customMessage ?: 'It must begin with a capital letter']];

        $this->Validator->firstLetterCapitalized('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::greaterThanDateTimeField()
     */
    #[Test]
    #[TestWith(['', '2025-02-26 13:00:00'])]
    #[TestWith(['', '2025-02-26'])]
    #[TestWith(['', new DateTime('2025-02-26 13:00:00')])]
    #[TestWith(['2025-02-26 13:00:01', '2025-02-26 13:00:00'])]
    #[TestWith(['2025-02-26', '2025-02-25'])]
    #[TestWith([new DateTime('2025-02-26 13:00:01'), new DateTime('2025-02-26 13:00:00')])]
    #[TestWith([new Date('2025-02-26'), new Date('2025-02-25')])]
    #[TestWith([new DateTime('2025-02-26 13:00:01'), new Date('2025-02-25')])]
    #[TestWith([new Date('2025-02-26'), new DateTime('2025-02-25 13:00:00')])]
    public function testGreaterThanDateTimeField(DateTime|Date|string $first_field, DateTime|Date|string $second_field): void
    {
        $this->Validator->greaterThanDateTimeField('first_field', 'second_field');

        $result = $this->Validator->validate(compact('first_field', 'second_field'));
        $this->assertEmpty($result);
    }

    /**
     * Tests for the `greaterThanDateTimeField()` method, without the second field.
     *
     * @link \Cake\Essentials\Validation\Validator::greaterThanDateTimeField()
     */
    #[Test]
    public function testGreaterThanDateTimeFieldWithoutSecondField(): void
    {
        $this->Validator->greaterThanDateTimeField('first_field', 'second_field');

        $result = $this->Validator->validate(['first_field' => '2025-02-26 13:00:00']);
        $this->assertEmpty($result);
    }

    /**
     * Tests for the `greaterThanDateTimeField()` method, on error.
     *
     * @link \Cake\Essentials\Validation\Validator::greaterThanDateTimeField()
     */
    #[Test]
    #[TestWith(['2025-02-26 12:59:59', '2025-02-26 13:00:00'])]
    #[TestWith(['2025-02-26 13:00:00', '2025-02-26 13:00:00'])]
    #[TestWith(['2025-02-26', '2025-02-27'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 13:00:00')])]
    #[TestWith([new Date('2025-02-26'), new Date('2025-02-27')])]
    #[TestWith([new DateTime('2025-02-25 13:00:00'), new Date('2025-02-26')])]
    #[TestWith([new Date('2025-02-26'), new DateTime('2025-02-26 13:00:00')])]
    #[TestWith(['2025-02-26 13:00:00', '2025-02-26 13:00:00', 'Bad `$second_field`'])]
    public function testGreaterThanDateTimeFieldOnError(
        DateTime|Date|string $first_field,
        DateTime|Date|string $second_field,
        string $customMessage = '',
    ): void {
        $expectedMessage = $customMessage ?: 'It must be greater than `' . toDateTime($second_field)->i18nFormat() . '`';
        $expected = ['first_field' => ['greaterThanDateTimeField' => $expectedMessage]];

        $this->Validator->greaterThanDateTimeField('first_field', 'second_field', $customMessage);

        $result = $this->Validator->validate(compact('first_field', 'second_field'));
        $this->assertSame($expected, $result);
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::greaterThanDateTime()
     */
    #[Test]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 13:00:01'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 13:00:01')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new Date('2025-02-27')])]
    #[TestWith([new Date('2025-02-25'), new DateTime('2025-02-26 13:00:01')])]
    #[TestWith([new Date('2025-02-25'), new Date('2025-02-26')])]
    public function testGreaterThanDateTime(DateTime|Date $comparisonValue, DateTime|Date|string $dateOrDateTime): void
    {
        $this->Validator->greaterThanDateTime('created', $comparisonValue);

        $this->assertEmpty($this->Validator->validate(['created' => $dateOrDateTime]));
    }

    /**
     * Tests for the `greaterThanDateTime()` method, on error.
     *
     * @link \Cake\Essentials\Validation\Validator::greaterThanDateTime()
     */
    #[Test]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 12:59:59'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 13:00:00'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 12:59:59')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new Date('2025-02-25')])]
    #[TestWith([new Date('2025-02-27'), new DateTime('2025-02-26 12:59:59')])]
    #[TestWith([new Date('2025-02-27'), new Date('2025-02-26')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 12:59:59'), 'You cannot use a past datetime'])]
    public function testGreaterThanDateTimeOnError(
        DateTime|Date $comparisonValue,
        DateTime|Date|string $badDateOrDateTime,
        string $customMessage = '',
    ): void {
        $expected = ['created' => [
            'greaterThanDateTime' => $customMessage ?: 'It must be greater than `' . $comparisonValue->i18nFormat() . '`',
        ]];

        $this->Validator->greaterThanDateTime('created', $comparisonValue, $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['created' => $badDateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::greaterThanOrEqualsDateTime()
     */
    #[Test]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 13:00:00'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 13:00:01'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-27'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 13:00:00')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 13:00:01')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new Date('2025-02-27')])]
    #[TestWith([new Date('2025-02-25'), new DateTime('2025-02-26 13:00:00')])]
    #[TestWith([new Date('2025-02-25'), new Date('2025-02-25')])]
    #[TestWith([new Date('2025-02-25'), new Date('2025-02-26')])]
    public function testGreaterThanOrEqualsDateTime(DateTime|Date $comparisonValue, DateTime|Date|string $dateOrDateTime): void
    {
        $this->Validator->greaterThanOrEqualsDateTime('created', $comparisonValue);

        $this->assertEmpty($this->Validator->validate(['created' => $dateOrDateTime]));
    }

    /**
     * Tests for the `greaterThanOrEqualsDateTime()` method, on error.
     *
     * @link \Cake\Essentials\Validation\Validator::greaterThanOrEqualsDateTime()
     */
    #[Test]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-26 12:59:59'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), '2025-02-25'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 12:59:59')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new Date('2025-02-25')])]
    #[TestWith([new Date('2025-02-26'), new Date('2025-02-25')])]
    #[TestWith([new Date('2025-02-27'), new DateTime('2025-02-26 12:59:59')])]
    #[TestWith([new DateTime('2025-02-26 13:00:00'), new DateTime('2025-02-26 12:59:59'), 'You cannot use a past datetime'])]
    public function testGreaterThanOrEqualsDateTimeOnError(
        DateTime|Date $comparisonValue,
        DateTime|Date|string $badDateOrDateTime,
        string $customMessage = '',
    ): void {
        $expected = [
            'created' => [
                'greaterThanOrEqualsDateTime' => $customMessage ?: 'It must be greater than or equal to `' . $comparisonValue->i18nFormat() . '`',
            ],
        ];

        $this->Validator->greaterThanOrEqualsDateTime('created', $comparisonValue, $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['created' => $badDateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::noStartOrEndSpace()
     */
    #[Test]
    #[TestWith(['A'])]
    #[TestWith(['A B'])]
    #[TestWith(['A B C'])]
    public function testNoStartOrEndSpace(string $stringNotStartingOrEndingWithSpace): void
    {
        $this->Validator->noStartOrEndSpace('text');

        $this->assertEmpty($this->Validator->validate(['text' => $stringNotStartingOrEndingWithSpace]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::noStartOrEndSpace()
     */
    #[Test]
    #[TestWith([' A'])]
    #[TestWith(['A '])]
    #[TestWith([' A '])]
    #[TestWith(['A ', 'You cannot use a space at the end'])]
    public function testNoStartOrEndSpaceOnError(string $badText, string $customMessage = ''): void
    {
        $expected = [
            'text' => [
                'noStartOrEndSpace' => $customMessage ?: 'It cannot contain spaces at the beginning or at the end',
            ],
        ];

        $this->Validator->noStartOrEndSpace('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notContainsReservedWords()
     */
    #[Test]
    public function testNotContainsReservedWords(): void
    {
        $this->Validator->notContainsReservedWords('text');

        $this->assertEmpty($this->Validator->validate(['text' => 'any reserved word...']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notContainsReservedWords()
     */
    #[Test]
    #[TestWith(['It cannot contain the reserved word `admin`', 'admin'])]
    #[TestWith(['It cannot contain the reserved word `manager`', 'manager'])]
    #[TestWith(['It cannot contain the reserved word `root`', 'root'])]
    #[TestWith(['It cannot contain the reserved word `supervisor`', 'supervisor'])]
    #[TestWith(['It cannot contain the reserved word `moderator`', 'moderator'])]
    #[TestWith(['It cannot contain the reserved word `mail`', 'mail'])]
    #[TestWith(['It cannot contain the reserved word `pwd`', 'pwd'])]
    #[TestWith(['It cannot contain the reserved word `password`', 'password'])]
    #[TestWith(['It cannot contain the reserved word `passwd`', 'passwd'])]
    #[TestWith(['It cannot contain the reserved word `admin`', '1admin2'])]
    #[TestWith(['It cannot contain the reserved word `aDmin`', '1aDmin2'])]
    #[TestWith(['It cannot contain the reserved word `1234`', 'ab1234cd'])]
    #[TestWith(['It cannot contain the reserved word `5678`', 'ab5678cd'])]
    #[TestWith(['It cannot contain the reserved word `7890`', 'ab7890cd'])]
    #[TestWith(['It cannot contain the reserved word `0000`', 'ab0000cd'])]
    #[TestWith(['It cannot contain the reserved word `1234`', 'ab1234567890cd'])]
    #[TestWith(['It cannot contain the reserved word `juventus`', '12juventus34'])]
    #[TestWith(['It cannot contain the reserved word `nApOli`', '12nApOli34'])]
    #[TestWith(['It cannot contain the reserved word `MILAN`', '12MILAN34'])]
    #[TestWith(['It cannot contain the reserved word `inter`', '12inter34'])]
    #[TestWith(['You cannot use a reserved word', 'admin', 'You cannot use a reserved word'])]
    public function testNotContainsReservedWordsOnError(string $expectedError, string $badText, string $customMessage = ''): void
    {
        $expected = ['text' => ['notContainsReservedWords' => $expectedError]];

        $this->Validator->notContainsReservedWords('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDatetime()
     */
    #[Test]
    #[TestWith(['now'])]
    #[TestWith(['yesterday'])]
    #[TestWith(['2026-02-13 00:00:00'])]
    #[TestWith(['2026-02-13'])]
    #[TestWith([new DateTime()])]
    #[TestWith([new DateTime('yesterday')])]
    #[TestWith([new Date()])]
    #[TestWith([new Date('yesterday')])]
    public function testNotFutureDatetime(DateTime|Date|string $dateOrDateTime): void
    {
        $this->Validator->notFutureDatetime('datetime');

        $this->assertEmpty($this->Validator->validate(['datetime' => $dateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDatetime()
     */
    #[Test]
    #[TestWith(['tomorrow'])]
    #[TestWith(['2099-02-13 00:00:00'])]
    #[TestWith(['2099-02-13'])]
    #[TestWith([new DateTime('tomorrow')])]
    #[TestWith([new Date('tomorrow')])]
    #[TestWith(['tomorrow', 'You cannot use a bad datetime'])]
    public function testNotFutureDatetimeOnError(DateTime|Date|string $badDateOrDateTime, string $customMessage = ''): void
    {
        $expected = ['datetime' => ['notFutureDatetime' => $customMessage ?: 'It cannot be a future datetime']];

        $this->Validator->notFutureDatetime('datetime', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['datetime' => $badDateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDatetime()
     */
    #[Test]
    #[TestWith(['tomorrow'])]
    #[TestWith([new DateTime('tomorrow')])]
    #[TestWith([new Date('tomorrow')])]
    public function testNotPastDatetime(DateTime|Date|string $dateOrDateTime): void
    {
        $this->Validator->notPastDatetime('datetime');

        $this->assertEmpty($this->Validator->validate(['datetime' => $dateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDatetime()
     */
    #[Test]
    #[TestWith(['yesterday'])]
    #[TestWith([new DateTime('yesterday')])]
    #[TestWith([new Date('yesterday')])]
    #[TestWith(['yesterday', 'You cannot use a bad datetime'])]
    public function testNotPastDatetimeOnError(DateTime|Date|string $badDateOrDateTime, string $customMessage = ''): void
    {
        $expected = ['datetime' => ['notPastDatetime' => $customMessage ?: 'It cannot be a past datetime']];

        $this->Validator->notPastDatetime('datetime', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['datetime' => $badDateOrDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::personName()
     */
    #[Test]
    #[TestWith(['Po'])]
    #[TestWith(['Mark'])]
    #[TestWith(['Àbate'])]
    #[TestWith(['D\'Alessandria'])]
    #[TestWith(['De Sanctis'])]
    #[TestWith(['Di Caprio'])]
    #[TestWith(['De La Cruz'])]
    #[TestWith(['Della Rovere'])]
    #[TestWith(['Lo Monaco'])]
    #[TestWith(['O\'Neill'])]
    public function testPersonName(string $goodName): void
    {
        $this->Validator->personName('name');

        $this->assertEmpty($this->Validator->validate(['name' => $goodName]));
    }

    /**
     * @param array<string, string> $expectedErrorMessage
     * @param string $badName
     * @param string $customMessage
     *
     * @link \Cake\Essentials\Validation\Validator::personName()
     */
    #[Test]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], 'Mark '])]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], ' Mark'])]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], ' Mark '])]
    #[TestWith([['minLength' => 'It must be at least 2 characters long'], 'P'])]
    #[TestWith([['maxLength' => 'It must be at a maximum of 40 characters long'], 'Massimiliano Alessandro Della Valle Rossi'])]
    #[TestWith([['firstLetterCapitalized' => 'It must begin with a capital letter'], 'mark'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'D\''])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'D\'\'Alessandria'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'De--Luca'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'Red-'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'Re$$d'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'MArk'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'M1rk'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'Mark red'])]
    #[TestWith([['personName' => 'It must be a valid person name'], 'Mark - Red'])]
    #[TestWith([['personName' => 'You cannot use a bad person name'], 'Mark - Red', 'You cannot use a bad person name'])]
    public function testPersonNameOnError(array $expectedErrorMessage, string $badName, string $customMessage = ''): void
    {
        $expected = ['my_field' => $expectedErrorMessage];

        $this->Validator->personName('my_field', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['my_field' => $badName]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::slug()
     */
    #[Test]
    #[TestWith(['abc'])]
    #[TestWith(['a-b'])]
    #[TestWith(['ab2'])]
    #[TestWith(['a-b2'])]
    public function testSlug(string $goodSlug): void
    {
        $this->Validator->slug('my_field');

        $this->assertEmpty($this->Validator->validate(['my_field' => $goodSlug]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::slug()
     */
    #[Test]
    #[TestWith(['Abc'])]
    #[TestWith(['aBc'])]
    #[TestWith(['a/b'])]
    #[TestWith(['a\\b'])]
    #[TestWith(['a_b'])]
    #[TestWith(['aàa'])]
    #[TestWith(['aàa', 'You cannot use a bad slug'])]
    public function testSlugOnError(string $badSlug, string $customMessage = ''): void
    {
        $expected = ['my_field' => ['slug' => $customMessage ?: 'It must be a valid slug']];

        $this->Validator->slug('my_field', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['my_field' => $badSlug]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::title()
     */
    #[Test]
    #[TestWith(['Title 2025'])]
    #[TestWith(['Title, 2025'])]
    #[TestWith(['Title: subtitle 2025'])]
    #[TestWith(['Élite 2025 / chapter (1)'])]
    #[TestWith(['Title, with-apostrophe\'s and (round brackets)'])]
    public function testTitle(string $goodTitle): void
    {
        $this->Validator->title('my_field');

        $this->assertEmpty($this->Validator->validate(['my_field' => $goodTitle]));
    }

    /**
     * @param array<string, string> $expectedErrorMessage
     * @param string $badTitle
     * @param string $customMessage
     *
     * @link \Cake\Essentials\Validation\Validator::title()
     */
    #[Test]
    #[TestWith([['minLength' => 'It must be at least 3 characters long'], 'P'])]
    #[TestWith([['minLength' => 'It must be at least 3 characters long'], 'Po'])]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], ' Title'])]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], 'Title '])]
    #[TestWith([['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end'], ' Title '])]
    #[TestWith([['firstLetterCapitalized' => 'It must begin with a capital letter'], 'uppercase title'])]
    #[TestWith([['title' => 'It must be a valid title'], 'Title$$$'])]
    #[TestWith([['title' => 'It must be a valid title'], 'Title!'])]
    #[TestWith([['title' => 'It must be a valid title'], 'Title?'])]
    #[TestWith([['title' => 'It must be a valid title'], 'Title; subtitle 2025'])]
    #[TestWith([['title' => 'You cannot use a bad title'], 'Title?', 'You cannot use a bad title'])]
    public function testTitleOnError(array $expectedErrorMessage, string $badTitle, string $customMessage = ''): void
    {
        $expected = ['my_field' => $expectedErrorMessage];

        $this->Validator->title('my_field', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['my_field' => $badTitle]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::validPassword()
     */
    #[Test]
    public function testValidPassword(): void
    {
        $this->Validator->validPassword('password');

        $this->assertEmpty($this->Validator->validate(['password' => 'ABCdef1gH3!?']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::validPassword()
     */
    #[Test]
    #[TestWith(['minLength', 'The provided value must be at least `12` characters long', 'abcd1534Ab!'])]
    #[TestWith(['containsDigit', 'It must contain at least one numeric digit', 'abcdefG!abcd'])]
    #[TestWith(['containsCapitalLetter', 'It must contain at least one capital character', 'abcdef1!abcd'])]
    #[TestWith(['containsLowercaseLetter', 'It must contain at least one lowercase character', 'ABCDEF1!1634'])]
    #[TestWith(['notAlphaNumeric', 'It must contain at least one special character', 'ABCDEf12abcd'])]
    #[TestWith(['notContainsReservedWords', 'It cannot contain the reserved word `Admin`', 'Admin213!abcd'])]
    #[TestWith(['notContainsReservedWords', 'Custom error message', 'Admin213!abcd', 'Custom error message'])]
    public function testValidPasswordOnError(string $expectedErrorName, string $expectedMessage, string $badPassword, ?string $customMessage = null): void
    {
        $expected = ['password' => [$expectedErrorName => $expectedMessage]];

        $this->Validator->validPassword('password', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }
}
