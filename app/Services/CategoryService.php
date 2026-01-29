<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class CategoryService
{

    public function __construct(private readonly EntityManager $entityManager)
    {
    }


    public function create(string $name, User $user): Category
    {

        $category = new Category();

        $category->setName($name);
        $category->setUser($user);



        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    public function getAll()
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    public function destroy(int $id) : void
    {
        $cat = $this->entityManager->getRepository(Category::class)->find($id);

        $this->entityManager->remove($cat);
        $this->entityManager->flush();
    }

    public function getById(int $param) : ?Category
    {
        return $this->entityManager->getRepository(Category::class)->find($param);
    }
}