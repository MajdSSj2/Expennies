<?php

namespace App\services;

use App\Contracts\AuthServiceInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserServiceInterface;

class AuthService implements AuthServiceInterface
{
    private ?UserInterface $user = null;

    public function __construct(private readonly UserServiceInterface $userService)
    {
    }


    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if (empty($_SESSION['user'])) {
            return null;
        }

        $userId = $_SESSION['user'];

        $user = $this->userService->getById($userId);

        if (!$user) {
            return null;
        }

        return $this->user = $user;
    }

    public function attemptLogin(array $credentials): bool
    {

        $user = $this->userService->getByEmail($credentials['email']);

        if (!$user || !password_verify($credentials['password'], $user->getPassword())) {
          return false;
        }
        $_SESSION['user'] = $user->getId();
        session_regenerate_id();

        return true;
    }

    public function logout(): void
    {
       $this->user = null;
    unset($_SESSION['user']);
       session_destroy();

    }
}