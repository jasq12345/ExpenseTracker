<?php

namespace App\Service;

use App\Dto\Category\CreateCategoryDto;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

readonly class CategoryService
{
    public function __construct(
        private UserProviderService $userProvider,
        private EntityManagerInterface $em,
    ) {}

    public function create(CreateCategoryDto $dto): Category
    {
        $user = $this->userProvider->getUser();

        $category = new Category();

        $category->setName($dto->name);
        $category->setIcon($dto->icon);
        $category->setColor($dto->color);

        $user->addCategory($category);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function update(Category $category, CreateCategoryDto $dto): Category
    {
        $category->setName($dto->name);
        $category->setIcon($dto->icon);
        $category->setColor($dto->color);

        $this->em->flush();

        return $category;
    }

    public function delete(Category $category): void
    {
        $user = $this->userProvider->getUser();

        $user->removeCategory($category);
        $this->em->flush();
    }

    public function getAll(): array
    {
        $user = $this->userProvider->getUser();
        
        return $user->getCategories()->toArray();
    }
}
