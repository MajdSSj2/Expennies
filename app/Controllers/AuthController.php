<?php

namespace App\Controllers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\DataObjects\registerUser;
use App\Exceptions\ValidationException;
use App\Validators\LoginUserRequestValidator;
use App\Validators\RegisterUserRequestValidator;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;


class AuthController
{
    public function __construct(
        private readonly Twig                             $twig,
        private readonly AuthServiceInterface             $auth,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory
    )
    {
    }

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {

        return $this->twig->render($response, 'auth/register.twig');
    }


    public function register(Request $request, Response $response): Response
    {
        $data = ($this->requestValidatorFactory)->make(registerUserRequestValidator::class)
            ->validate($request->getParsedBody());


        $this->auth->register(new registerUser($data['name'], $data['email'], $data['password']));

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);    }

    public function login(Request $request, Response $response): Response
    {
        $data = ($this->requestValidatorFactory)->make(LoginUserRequestValidator::class)
            ->validate($request->getParsedBody());

        if (!$this->auth->attemptLogin($data)) {
            throw new ValidationException(['email' => ['You have entered an invalid username or password']]);
        }

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}