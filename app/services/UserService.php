<?php

namespace App\services;

use App\Contracts\UserInterface;
use App\Contracts\UserServiceInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService implements UserServiceInterface{

    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function getById(int $id): ?UserInterface
    {
       return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getByEmail(string $email): ?UserInterface
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $credentials['email']]);
    }
}