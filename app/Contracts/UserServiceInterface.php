<?php

namespace App\Contracts;

interface UserServiceInterface
{
    public function getById(int $id): ?UserInterface;
    public function getByEmail(string $email): ?UserInterface;

}