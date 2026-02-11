<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function findByPeriod(User $user, DateTime $start, DateTime $end): array
    {
        $qb = $this->createQueryBuilder('t');

        $qb->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->between('t.date', ':start', ':end'))
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.date', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function getTotalByPeriod(User $user, DateTime $start, DateTime $end): float
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('SUM(t.price * t.amount) as total')
            ->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->between('t.date', ':start', ':end'))
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        $result = $qb->getQuery()->getSingleScalarResult();

        return $result !== null ? (float) $result : 0.0;
    }

    public function getTotalByPeriodAndCategory(User $user, DateTime $start, DateTime $end, Category $category): float
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('SUM(t.price * t.amount) as total')
            ->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->eq('t.category', ':category'))
            ->andWhere($qb->expr()->between('t.date', ':start', ':end'))
            ->setParameter('user', $user)
            ->setParameter('category', $category)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        $result = $qb->getQuery()->getSingleScalarResult();

        return $result !== null ? (float) $result : 0.0;
    }
}
