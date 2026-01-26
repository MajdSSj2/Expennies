<?php

namespace App\Middleware;

use App\Contracts\SessionInterface;
use App\Exceptions\ValidationException;
use App\Services\ServerRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory,
                                public readonly SessionInterface          $session,
                                private readonly ServerRequest            $serverRequest
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $ex) {

            $response = $this->responseFactory->createResponse();

            $referer = $this->serverRequest->getReferer($request);

            $oldData = $request->getParsedBody();

            $sensitiveFields = ['password', 'confirmPassword'];

            $this->session->flash('errors', $ex->errors);
            $this->session->flash('old', array_diff_key($oldData, array_flip($sensitiveFields)));

            return $response->withHeader('Location', $referer)->withStatus(302);

        }
    }
}