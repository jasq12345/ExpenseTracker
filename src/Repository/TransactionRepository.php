<?php

namespace App\Repository;

use App\Dto\Pagination\PaginationDto;
use App\Dto\Transaction\ListTransactionDto;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use App\Enum\TransactionType;
use App\ValueObject\Period;
use DateTime;
use DateTimeImmutable;
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

//    public function findByPeriod(User $user, ?DateTimeImmutable $start, ?DateTimeImmutable $end): array
//    {
//        $qb = $this->createQueryBuilder('t');
//
//        $qb->andWhere($qb->expr()->eq('t.user', ':user'))
//            ->setParameter('user', $user)
//            ->orderBy('t.createdAt', 'DESC');
//
//        if ($start !== null) {
//            $qb->andWhere($qb->expr()->gte('t.createdAt', ':start'))
//                ->setParameter('start', $start);
//        }
//
//        if ($end !== null) {
//            $qb->andWhere($qb->expr()->lte('t.createdAt', ':end'))
//                ->setParameter('end', $end);
//        }
//
//        return $qb->getQuery()->getResult();
//    }

    public function getTotalByPeriodAndType(
        User $user,
        TransactionType $type,
        Period $period,
        ?array $categories = null
    ): float
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('COALESCE(SUM(t.price * t.amount), 0) as total')
            ->andWhere($qb->expr()->eq('t.user', ':user'))
            ->andWhere($qb->expr()->eq('t.type', ':type'))
            ->setParameter('user', $user)
            ->setParameter('type', $type);

        if ($period->getStartDate() !== null) {
            $qb->andWhere($qb->expr()->gte('t.createdAt', ':start'))
                ->setParameter('start', $period->getStartDate());
        }

        if ($period->getEndDate() !== null) {
            $qb->andWhere($qb->expr()->lte('t.createdAt', ':end'))
                ->setParameter('end', $period->getEndDate());
        }

        if ($categories !== null && count($categories) > 0) {
            $qb->andWhere($qb->expr()->in('t.category', ':categories'))
                ->setParameter('categories', $categories);
        }

        return (float) $qb->getQuery()->getSingleScalarResult();
    }

    public function listByUser(User $user, ListTransactionDto $dto): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->setFirstResult(($dto->page - 1) * $dto->limit)
            ->setMaxResults($dto->limit)
            ->getQuery()
            ->getResult();
    }

    public function countByUser(User $user): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
