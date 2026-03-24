<?php

namespace App\Repository;

use App\Entity\Budget;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function listByUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->orderBy('b.year', 'DESC')
            ->addOrderBy('b.month', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
