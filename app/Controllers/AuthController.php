<?php

namespace App\Controllers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\SessionInterface;
use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Valitron\Validator;


class AuthController
{
    public function __construct(
        private readonly Twig                 $twig,
        private readonly EntityManager        $entityManager,
        private readonly AuthServiceInterface $auth,
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
        $data = $request->getParsedBody();

        $user = new User();

        $v = new Validator($data);

        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'password', 'confirmPassword');
        $v->rule(fn($field, $value, $params, $fields) => !$this->entityManager->getRepository(User::class)
            ->count(['email' => $value]), 'email')
            ->message('User with this email already exists');
        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        $user->setEmail($data['email']);

        $user->setPassword(Password_hash($data['password'], PASSWORD_BCRYPT));

        $user->setName($data['name']);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $response;
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

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