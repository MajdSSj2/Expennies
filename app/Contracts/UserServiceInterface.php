<?php

namespace App\Contracts;

use App\DataObjects\registerUser;

interface UserServiceInterface
{
    public function getById(int $id): ?UserInterface;
    public function getByCredentials(array $credentials): ?UserInterface;

    public function createUser(registerUser $data): UserInterface;

}