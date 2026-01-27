<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard;
use Slim\Views\Twig;

class csrfFieldsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ContainerInterface $container, private readonly Twig $twig)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = $this->container->get('csrf');

        // CSRF token name and value
        $csrfNameKey = $csrf->getTokenNameKey();
        $csrfValueKey = $csrf->getTokenValueKey();
        $csrfName = $csrf->getTokenName();
        $csrfValue = $csrf->getTokenValue();;
        $this->twig->getEnvironment()->addGlobal('csrf', [
            'keys' => [
                'name' => $csrfNameKey,
                'value' => $csrfValueKey
            ],
            'name' => $csrfName,
            'value' => $csrfValue,
            'fields' => <<<CSRF_FIELDS
                        <input type="hidden" name="$csrfNameKey" value="$csrfName">
                        <input type="hidden" name="$csrfValueKey" value="$csrfValue">
                        CSRF_FIELDS,
        ]);
        return $handler->handle($request);
    }
}