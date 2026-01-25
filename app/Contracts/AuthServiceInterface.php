<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function user(): ?UserInterface ;

    public function attemptLogin(array $credentials): bool;

    public function logout(): void;
}