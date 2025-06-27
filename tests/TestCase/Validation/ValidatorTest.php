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

    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a capital letter'])]
    public function testContainsCapitalLetterOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsCapitalLetter' => $customMessage ?: 'It must contain at least one capital character']];

        $this->Validator->containsCapitalLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'a3!']));
    }

    #[Test]
    #[TestWith(['3'])]
    #[TestWith(['Ab3!'])]
    public function testContainsDigit(string $goodString): void
    {
        $this->Validator->containsDigit('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodString]));
    }

    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a digit'])]
    public function testContainsDigitOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsDigit' => $customMessage ?: 'It must contain at least one numeric digit']];

        $this->Validator->containsDigit('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'Ab!']));
    }

    #[Test]
    #[TestWith(['a'])]
    #[TestWith(['aB3!'])]
    public function testContainsLowercaseLetter(string $goodString): void
    {
        $this->Validator->containsLowercaseLetter('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodString]));
    }

    #[Test]
    #[TestWith([])]
    #[TestWith(['Does not contain a lowercase letter'])]
    public function testContainsLowercaseLetterOnError(string $customMessage = ''): void
    {
        $expected = ['text' => ['containsLowercaseLetter' => $customMessage ?: 'It must contain at least one lowercase character']];

        $this->Validator->containsLowercaseLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'A3!']));
    }

    #[Test]
    #[TestWith(['Àbate'])]
    #[TestWith(['Text'])]
    public function testFirstLetterCapitalized(string $goodText): void
    {
        $this->Validator->firstLetterCapitalized('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodText]));
    }

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

    #[Test]
    #[TestWith(['A'])]
    #[TestWith(['A B'])]
    #[TestWith(['A B C'])]
    public function testNoStartOrEndSpace(string $goodText): void
    {
        $this->Validator->noStartOrEndSpace('text');

        $this->assertEmpty($this->Validator->validate(['text' => $goodText]));
    }

    #[Test]
    #[TestWith([' A'])]
    #[TestWith(['A '])]
    #[TestWith([' A '])]
    public function testNoStartOrEndSpaceOnError(string $badText, string $customMessage = ''): void
    {
        $expected = ['text' => ['noStartOrEndSpace' => 'It cannot contain spaces at the beginning or at the end']];

        $this->Validator->noStartOrEndSpace('text');

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    #[Test]
    public function testNotContainsReservedWords(): void
    {
        $this->Validator->notContainsReservedWords('text');

        $this->assertEmpty($this->Validator->validate(['text' => 'any reserved word...']));
    }

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

    #[Test]
    #[TestWith([new Date('tomorrow')])]
    #[TestWith(['tomorrow', 'You cannot use a bad date'])]
    public function testNotFutureDateOnError(Date|string $badDate, string $customMessage = ''): void
    {
        $expected = ['date' => ['notFutureDate' => $customMessage ?: 'It cannot be a future date']];

        $this->Validator->notFutureDate('date', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['date' => $badDate]));
    }

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

    #[Test]
    #[TestWith([new Date('yesterday')])]
    #[TestWith(['yesterday', 'You cannot use a bad date'])]
    public function testNotPastDateOnError(Date|string $badDate, string $customMessage = ''): void
    {
        $expected = ['date' => ['notPastDate' => $customMessage ?: 'It cannot be a past date']];

        $this->Validator->notPastDate('date', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['date' => $badDate]));
    }

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

    #[Test]
    #[TestWith(['À'])]
    #[TestWith(['P'])]
    #[TestWith(['D\''])]
    #[TestWith(['D\'\'Alessandria'])]
    #[TestWith(['De--Luca'])]
    #[TestWith(['Red-'])]
    #[TestWith(['Re$$d'])]
    #[TestWith(['MArk'])]
    #[TestWith(['M1rk'])]
    #[TestWith(['Mark '])]
    #[TestWith(['MArk '])]
    #[TestWith(['Mark red'])]
    #[TestWith(['Mark - Red'])]
    #[TestWith(['Mark - Red', 'You cannot use a bad person name'])]
    public function testPersonNameOnError(string $badName, string $customMessage = ''): void
    {
        $expected = ['name' => ['personName' => $customMessage ?: 'It must be a valid person name']];

        $this->Validator->personName('name', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['name' => $badName]));
    }

    #[Test]
    public function testPersonNameOnFirstLetterNotCapitalized(): void
    {
        $expected = ['name' => [
            'firstLetterCapitalized' => 'It must begin with a capital letter',
            'personName' => 'It must be a valid person name',
        ]];

        $this->Validator->personName('name');

        $this->assertSame($expected, $this->Validator->validate(['name' => 'mark']));
    }

    #[Test]
    public function testPersonNameOnErrorMaxLength(): void
    {
        $expected = ['name' => ['maxLength' => 'The provided value must be at most `40` characters long']];

        $this->Validator->personName('name');

        $this->assertSame($expected, $this->Validator->validate(['name' => 'A' . str_repeat('a', 40)]));
    }

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

    #[Test]
    #[TestWith(['P'])]
    #[TestWith(['Po'])]
    #[TestWith(['Title '])]
    #[TestWith(['Title$$$'])]
    #[TestWith(['Title!'])]
    #[TestWith(['Title?'])]
    #[TestWith(['Title; subtitle 2025'])]
    #[TestWith(['Title?', 'You cannot use a bad title'])]
    public function testTitleOnError(string $badTitle, string $customMessage = ''): void
    {
        $expected = ['title' => ['title' => $customMessage ?: 'It must be a valid title']];

        $this->Validator->title('title', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['title' => $badTitle]));
    }

    #[Test]
    #[TestWith(['uppercase title'])]
    #[TestWith([' Title'])]
    public function testTitleOnFirstLetterNotCapitalized(): void
    {
        $expected = ['title' => [
            'firstLetterCapitalized' => 'It must begin with a capital letter',
            'title' => 'It must be a valid title',
        ]];

        $this->Validator->title('title');

        $this->assertSame($expected, $this->Validator->validate(['title' => 'uppercase title']));
    }

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

    #[Test]
    #[DataProvider('invalidPasswordsDataProvider')]
    public function testValidPasswordOnError(string $expectedErrorName, string $expectedMessage, string $badPassword): void
    {
        $expected = ['password' => [$expectedErrorName => $expectedMessage]];

        $this->Validator->validPassword('password');

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }

    #[Test]
    #[DataProvider('invalidPasswordsDataProvider')]
    public function testValidPasswordOnErrorWithCustomMessage(string $expectedErrorName, string $expectedMessage, string $badPassword): void
    {
        $expected = ['password' => [$expectedErrorName => 'Custom error message']];

        $this->Validator->validPassword('password', 'Custom error message');

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }
}
