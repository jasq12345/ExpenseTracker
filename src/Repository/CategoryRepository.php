<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function isSystemCategory(Category $category): bool
    {
        return $category->getUser() === null;
    }

    public function findOneByIdAndUser(int $id, User $user): Category
    {
        $category = $this->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);

        if (!$category) {
            throw new DomainException('Category not found.');
        }

        return $category;
    }
}
