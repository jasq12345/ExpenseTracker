<?php

namespace App\Repository;

use App\Dto\Budget\ListBudgetDto;
use App\Dto\Pagination\PaginationDto;
use App\Entity\Budget;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DomainException;

/**
 * @extends ServiceEntityRepository<Budget>
 */
class BudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Budget::class);
    }

    public function findByMonth(User $user, int $month, int $year): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->andWhere('MONTH(t.month) = :month')
            ->andWhere('YEAR(t.month) = :year')
            ->setParameter('user', $user)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

    public function existsCurrentBudget(User $user): bool
    {
        return $this->findOneBy([
            'user' => $user,
            'month' => (int) date('m'),
            'year' => (int) date('Y')
        ]) !== null;
    }

    public function findCurrentBudgetByUser(User $user): ?Budget
    {
        return $this->findOneBy([
            'user' => $user,
            'month' => (int) date('m'),
            'year' => (int) date('Y')
        ]);
    }

    /**
     * @return Budget[]
     */
    public function listByUser(User $user, ListBudgetDto $dto): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.year', $dto->orderBy->toString())
            ->addOrderBy('b.month', $dto->orderBy->toString())
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()
            ->getResult();
    }

    public function countByUser(User $user): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
