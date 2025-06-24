<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Validation;

use Cake\Essentials\Validation\Validator;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
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
        $expected = ['text' => ['containsCapitalLetter' => $customMessage ?: 'Must contain at least one capital character']];

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
        $expected = ['text' => ['containsDigit' => $customMessage ?: 'Must contain at least one numeric digit']];

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
        $expected = ['text' => ['containsLowercaseLetter' => $customMessage ?: 'Must contain at least one lowercase character']];

        $this->Validator->containsLowercaseLetter('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => 'A3!']));
    }

    #[Test]
    public function testFirstLetterCapitalized(): void
    {
        $this->Validator->firstLetterCapitalized('text');

        $this->assertEmpty($this->Validator->validate(['text' => 'Text']));
    }

    #[Test]
    #[TestWith(['a'])]
    #[TestWith(['1'])]
    #[TestWith(['!'])]
    #[TestWith(['!'])]
    #[TestWith(['1', 'You cannot use a bad title'])]
    public function testFirstLetterCapitalizedOnError(string $badText, string $customMessage = ''): void
    {
        $expected = ['text' => ['firstLetterCapitalized' => $customMessage ?: 'Has to begin with a capital letter']];

        $this->Validator->firstLetterCapitalized('text', $customMessage);

        $this->assertSame($expected, $this->Validator->validate(['text' => $badText]));
    }

    #[Test]
    public function testValidPassword(): void
    {
        $this->Validator->validPassword('password');

        $this->assertEmpty($this->Validator->validate(['password' => 'ABCdef1!']));
    }

    #[Test]
    #[TestWith(['minLength', 'The provided value must be at least `8` characters long', '1234Ab!'])]
    #[TestWith(['containsDigit', 'Must contain at least one numeric digit', 'abcdefG!'])]
    #[TestWith(['containsCapitalLetter', 'Must contain at least one capital character', 'abcdef1!'])]
    #[TestWith(['containsLowercaseLetter', 'Must contain at least one lowercase character', 'ABCDEF1!'])]
    #[TestWith(['notAlphaNumeric', 'Must contain at least one special character', 'ABCDEf12'])]
    #[TestWith(['notContainReservedWords', 'Cannot contain any reserved words', 'Admin213!'])]
    public function testValidPasswordOnError(string $expectedErrorName, string $expectedMessage, string $badPassword): void
    {
        $expected = ['password' => [$expectedErrorName => $expectedMessage]];

        $this->Validator->validPassword('password');

        $this->assertSame($expected, $this->Validator->validate(['password' => $badPassword]));
    }
}
