<?php

namespace App;

use App\Contracts\AuthServiceInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserServiceInterface;

class Auth implements AuthServiceInterface
{
    private ?UserInterface $user = null;

    public function __construct(private readonly UserServiceInterface $userService,
                                private readonly SessionInterface     $session)
    {
    }


    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }


        $userId = $this->session->get('user') ?? null;

        if (!$userId) {
            return null;
        }

        $user = $this->userService->getById($userId);

        if (!$user) {
            return null;
        }
        $this->user = $user;
        return $this->user;
    }

    public function attemptLogin(array $credentials): bool
    {

        $user = $this->userService->getByCredentials($credentials);

        if (!$user || !$this->checkCredentials($user, $credentials)) {
            return false;
        }
        $this->session->put('user',$user->getId());

        $this->user = $user;

        $this->session->regenerate();

        return true;
    }

    public function checkCredentials(UserInterface $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->getPassword());
    }

    public function logout(): void
    {
        $this->session->forget('user');
        $this->user = null;


    }
}