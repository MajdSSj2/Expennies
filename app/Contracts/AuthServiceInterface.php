<?php

namespace App\Contracts;

use App\DataObjects\registerUser;

interface AuthServiceInterface
{
    public function user(): ?UserInterface ;

    public function attemptLogin(array $credentials): bool;

    public function logout(): void;

    public function register(registerUser $data): UserInterface;

    public function login(UserInterface $user): void;
}