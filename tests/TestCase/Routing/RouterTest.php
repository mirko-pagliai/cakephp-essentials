<?php
declare(strict_types=1);

namespace Cake\Essentials\Test\TestCase\Routing;

use Cake\Essentials\Routing\Router;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * RouterTest.
 */
#[CoversClass(Router::class)]
class RouterTest extends TestCase
{
    #[Test]
    public function testGetRequestOrFail(): void
    {
        $Request = new ServerRequest();
        Router::setRequest($Request);

        $result = Router::getRequestOrFail();
        $this->assertSame($Request, $result);
    }

    #[Test]
    public function testGetRequestOrFailWithNoRequest(): void
    {
        $this->expectExceptionMessage('Request is not an instance of `' . ServerRequest::class . '`');
        Router::getRequestOrFail();
    }
}
