<?php

namespace App\Repository;

use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 */
class RefreshTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    public function isExpired(RefreshToken $token): bool
    {
        return $token->getExpiresAt() < new \DateTimeImmutable();
    }

    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.expiresAt > :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('t.expiresAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

   public function deleteExpiredTokens()
   {
       return $this->createQueryBuilder('t')
           ->delete()
           ->where('t.expiresAt < :now')
           ->setParameter('now', new \DateTimeImmutable())
           ->getQuery()
           ->execute();
   }
}
