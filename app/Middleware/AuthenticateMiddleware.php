<?php

namespace App\Middleware;

use App\Contracts\AuthServiceInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthenticateMiddleware implements MiddlewareInterface
{
 public function __construct(private readonly  AuthServiceInterface $auth)
 {
 }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        return $handler->handle($request->withAttribute('user', $this->auth->user()));
    }
}