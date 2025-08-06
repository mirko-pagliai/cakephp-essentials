<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Validation;

use Cake\Essentials\Validation\Validator;
use Cake\I18n\Date;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;

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
    public function testContainsCapitalLetter(string $goodString): void
    {
        $this->Validator->containsCapitalLetter('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodString]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsCapitalLetter()
     */
    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a capital letter'])]
    public function testContainsCapitalLetterOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsCapitalLetter' => $customMessage ?: 'It must contain at least one capital character']];

        $this->Validator->containsCapitalLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'a3!']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsDigit()
     */
    #[Test]
    #[TestWith(['3'])]
    #[TestWith(['Ab3!'])]
    public function testContainsDigit(string $goodString): void
    {
        $this->Validator->containsDigit('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodString]));
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
    public function testContainsLowercaseLetter(string $goodString): void
    {
        $this->Validator->containsLowercaseLetter('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodString]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::containsLowercaseLetter()
     */
    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a lowercase letter'])]
    public function testContainsLowercaseLetterOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsLowercaseLetter' => $customMessage ?: 'It must contain at least one lowercase character']];

        $this->Validator->containsLowercaseLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'A3!']));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::firstLetterCapitalized()
     */
    #[Test]
    #[TestWith(['Àbate'])]
    #[TestWith(['Text'])]
    public function testFirstLetterCapitalized(string $goodText): void
    {
        $this->Validator->firstLetterCapitalized('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodText]));
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
     * @link \Cake\Essentials\Validation\Validator::greaterThanOrEqualsDateTime()
     */
    #[Test]
    #[TestWith(['2025-02-26 13:00:00'])]
    #[TestWith(['2025-02-26 13:00:01'])]
    #[TestWith([new DateTime('2025-02-26 13:00:00')])]
    #[TestWith([new DateTime('2025-02-26 13:00:01')])]
    public function testGreaterThanOrEqualsDateTime(DateTime|string $GoodDateTime): void
    {
        $this->Validator->greaterThanOrEqualsDateTime('created', new DateTime('2025-02-26 13:00:00'));

        $this->assertEmpty($this->Validator->validate(['created' => $GoodDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::greaterThanOrEqualsDateTime()
     */
    #[Test]
    #[TestWith(['2025-02-26 12:59:59'])]
    #[TestWith(['2025-02-26 12:00:00'])]
    #[TestWith([new DateTime('2025-02-26 12:59:59')])]
    #[TestWith([new DateTime('2025-02-26 12:00:00')])]
    #[TestWith([new DateTime('2025-02-26 12:00:00'), 'You cannot use a past datetime'])]
    public function testGreaterThanOrEqualsDateTimeOnError(DateTime|string $BadDateTime, string $customMessage = ''): void
    {
        $comparisonValue = new DateTime('2025-02-26 13:00:00');

        $expected = ['created' => [
            'greaterThanOrEqualsDateTime' => $customMessage ?: 'It must be greater than or equal to `' . $comparisonValue->i18nFormat() . '`',
        ]];

        $this->Validator->greaterThanOrEqualsDateTime('created', $comparisonValue, $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['created' => $BadDateTime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::noStartOrEndSpace()
     */
    #[Test]
    #[TestWith(['A'])]
    #[TestWith(['A B'])]
    #[TestWith(['A B C'])]
    public function testNoStartOrEndSpace(string $goodText): void
    {
        $this->Validator->noStartOrEndSpace('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodText]));
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
        $expected = ['text' => ['noStartOrEndSpace' => $customMessage ?: 'It cannot contain spaces at the beginning or at the end']];

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
    #[TestWith(['admin'])]
    #[TestWith(['manager'])]
    #[TestWith(['root'])]
    #[TestWith(['supervisor'])]
    #[TestWith(['moderator'])]
    #[TestWith(['mail'])]
    #[TestWith(['pwd'])]
    #[TestWith(['password'])]
    #[TestWith(['passwd'])]
    #[TestWith(['1admin2'])]
    #[TestWith(['1aDmin2'])]
    #[TestWith(['admin', 'You cannot use a reserved word'])]
    public function testNotContainsReservedWordsOnError(string $badText, string $customMessage = ''): void
    {
        $expected = ['text' => ['notContainReservedWords' => $customMessage ?: 'It cannot contain any reserved words']];

        $this->Validator->notContainsReservedWords('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDate()
     */
    #[Test]
    #[TestWith(['now'])]
    #[TestWith(['yesterday'])]
    #[TestWith([new Date()])]
    #[TestWith([new Date('yesterday')])]
    public function testNotFutureDate(Date|string $goodDate): void
    {
        $this->Validator->notFutureDate('date');

        $this->assertEmpty($this->Validator->validate(['date' => $goodDate]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDate()
     */
    #[Test]
    #[TestWith([new Date('tomorrow')])]
    #[TestWith(['tomorrow', 'You cannot use a bad date'])]
    public function testNotFutureDateOnError(Date|string $badDate, string $customMessage = ''): void
    {
        $expected = ['date' => ['notFutureDate' => $customMessage ?: 'It cannot be a future date']];

        $this->Validator->notFutureDate('date', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['date' => $badDate]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDatetime()
     */
    #[Test]
    #[TestWith(['now'])]
    #[TestWith(['yesterday'])]
    #[TestWith([new DateTime()])]
    #[TestWith([new DateTime('yesterday')])]
    public function testNotFutureDatetime(DateTime|string $goodDatetime): void
    {
        $this->Validator->notFutureDatetime('datetime');

        $this->assertEmpty($this->Validator->validate(['datetime' => $goodDatetime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notFutureDatetime()
     */
    #[Test]
    #[TestWith([new DateTime('+10 seconds')])]
    #[TestWith([new DateTime('tomorrow')])]
    #[TestWith(['tomorrow', 'You cannot use a bad datetime'])]
    public function testNotFutureDatetimeOnError(DateTime|string $badDatetime, string $customMessage = ''): void
    {
        $expected = ['datetime' => ['notFutureDatetime' => $customMessage ?: 'It cannot be a future datetime']];

        $this->Validator->notFutureDatetime('datetime', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['datetime' => $badDatetime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDate()
     */
    #[Test]
    #[TestWith(['now'])]
    #[TestWith(['tomorrow'])]
    #[TestWith([new Date()])]
    #[TestWith([new Date('tomorrow')])]
    public function testNotPastDate(Date|string $goodDate): void
    {
        $this->Validator->notPastDate('date');

        $this->assertEmpty($this->Validator->validate(['date' => $goodDate]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDate()
     */
    #[Test]
    #[TestWith([new Date('yesterday')])]
    #[TestWith(['yesterday', 'You cannot use a bad date'])]
    public function testNotPastDateOnError(Date|string $badDate, string $customMessage = ''): void
    {
        $expected = ['date' => ['notPastDate' => $customMessage ?: 'It cannot be a past date']];

        $this->Validator->notPastDate('date', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['date' => $badDate]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDatetime()
     */
    #[Test]
    #[TestWith(['+10 second'])]
    #[TestWith(['tomorrow'])]
    #[TestWith([new DateTime('+10 second')])]
    #[TestWith([new DateTime('tomorrow')])]
    public function testNotPastDatetime(DateTime|string $goodDatetime): void
    {
        $this->Validator->notPastDatetime('datetime');

        $this->assertEmpty($this->Validator->validate(['datetime' => $goodDatetime]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::notPastDatetime()
     */
    #[Test]
    #[TestWith([new DateTime('yesterday')])]
    #[TestWith(['yesterday'])]
    #[TestWith(['yesterday', 'You cannot use a bad datetime'])]
    public function testNotPastDatetimeOnError(DateTime|string $badDatetime, string $customMessage = ''): void
    {
        $expected = ['datetime' => ['notPastDatetime' => $customMessage ?: 'It cannot be a past datetime']];

        $this->Validator->notPastDatetime('datetime', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['datetime' => $badDatetime]));
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
    public function testPersonName(string $goodName): void
    {
        $this->Validator->personName('name');

        $this->assertEmpty($this->Validator->validate(['name' => $goodName]));
    }

    /**
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
        $expected = ['name' => $expectedErrorMessage];

        $this->Validator->personName('name', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['name' => $badName]));
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
        $this->Validator->slug('slug');

        $this->assertEmpty($this->Validator->validate(['slug' => $goodSlug]));
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
        $expected = ['slug' => ['slug' => $customMessage ?: 'It must be a valid slug']];

        $this->Validator->slug('slug', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['slug' => $badSlug]));
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
        $this->Validator->title('title');

        $this->assertEmpty($this->Validator->validate(['title' => $goodTitle]));
    }

    /**
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
        $expected = ['title' => $expectedErrorMessage];

        $this->Validator->title('title', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['title' => $badTitle]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::validPassword()
     */
    #[Test]
    public function testValidPassword(): void
    {
        $this->Validator->validPassword('password');

        $this->assertEmpty($this->Validator->validate(['password' => 'ABCdef1!']));
    }

    public static function invalidPasswordsDataProvider(): array
    {
        return [
            ['minLength', 'The provided value must be at least `8` characters long', '1234Ab!'],
            ['containsDigit', 'It must contain at least one numeric digit', 'abcdefG!'],
            ['containsCapitalLetter', 'It must contain at least one capital character', 'abcdef1!'],
            ['containsLowercaseLetter', 'It must contain at least one lowercase character', 'ABCDEF1!'],
            ['notAlphaNumeric', 'It must contain at least one special character', 'ABCDEf12'],
            ['notContainReservedWords', 'It cannot contain any reserved words', 'Admin213!'],
        ];
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::validPassword()
     */
    #[Test]
    #[DataProvider('invalidPasswordsDataProvider')]
    public function testValidPasswordOnError(string $expectedErrorName, string $expectedMessage, string $badPassword): void
    {
        $expected = ['password' => [$expectedErrorName => $expectedMessage]];

        $this->Validator->validPassword('password');

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }

    /**
     * @link \Cake\Essentials\Validation\Validator::validPassword()
     */
    #[Test]
    #[DataProvider('invalidPasswordsDataProvider')]
    public function testValidPasswordOnErrorWithCustomMessage(string $expectedErrorName, string $expectedMessage, string $badPassword): void
    {
        $expected = ['password' => [$expectedErrorName => 'Custom error message']];

        $this->Validator->validPassword('password', 'Custom error message');

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }
}
