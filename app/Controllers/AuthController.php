<?php

namespace App\Controllers;

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
        private readonly Twig $twig,
         private readonly EntityManager $entityManager,
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
        $v->rule(fn($field, $value, $params, $fields) =>
            !$this->entityManager->getRepository(User::class)
            ->count(['email' => $value]), 'email')
            ->message('Please enter a valid email address');
        if ($v->validate()) {
            echo 'passed';
        } else {
            throw new ValidationException($v->errors(), 'Validation failed', '422');
        }

        $user->setEmail($data['email']);

        $user->setPassword(Password_hash($data['password'], PASSWORD_BCRYPT));

        $user->setName($data['name']);

        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $response->withHeader('Content-Type', 'application/json');
    }
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $v = new Validator($data);
        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (! $v->validate()) {
            throw new ValidationException($v->errors(), 'Validation failed', '422');
        }

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            throw new ValidationException(
                ['error' => ['validation error']],
                'Email or password is incorrect',
                '401'
            );
        }

        session_regenerate_id();
        $_SESSION['user'] = $user->getId();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

}