<?php

namespace App\Repository;

use App\Dto\Category\ListCategoryDto;
use App\Dto\Pagination\PaginationDto;
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

    public function findOneByIdAndUser(int $id, User $user): ?Category
    {
        return $this->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);
    }

    public function listByUser(User $user, ListCategoryDto $dto): array
    {
        $qb =  $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit);

        if($dto->name){
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', '%' . $dto->name . '%');
        }

        if($dto->color){
            $qb->andWhere('c.color = :color')
                ->setParameter('color', $dto->color);
        }

        if($dto->icon){
            $qb->andWhere('c.icon = :icon')
                ->setParameter('icon', $dto->icon);
        }

        return $qb->getQuery()->getResult();
    }

    public function countByUser(User $user): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
