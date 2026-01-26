<?php

namespace App\Contracts;

interface UserServiceInterface
{
    public function getById(int $id): ?UserInterface;
    public function getByCredentials(array $credentials): ?UserInterface;

}