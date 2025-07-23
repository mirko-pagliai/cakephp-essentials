<?php
declare(strict_types=1);

namespace Cake\Essentials\Routing;

use Cake\Http\ServerRequest;
use Cake\Routing\Router as CakeRouter;
use RuntimeException;

/**
 * Class Router
 *
 * Extends the functionality of `Router` to provide additional request handling features.
 */
class Router extends CakeRouter
{
    /**
     * Retrieves the current server request or throws an exception if the request is not valid.
     *
     * @return \Cake\Http\ServerRequest The current server request instance.
     * @throws \RuntimeException If the request is not an instance of ServerRequest.
     */
    public static function getRequestOrFail(): ServerRequest
    {
        $request = self::getRequest();
        if (!$request instanceof ServerRequest) {
            throw new RuntimeException('Request is not an instance of `' . ServerRequest::class . '`');
        }

        return $request;
    }
}
