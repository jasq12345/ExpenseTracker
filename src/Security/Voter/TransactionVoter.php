<?php

namespace App\Security\Voter;

use App\Entity\Transaction;
use App\Entity\User;
use App\Enum\AccessPolicyEnum;

final class TransactionVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'TRANSACTION_VIEW';
    public const string EDIT = 'TRANSACTION_EDIT';
    public const string CREATE = 'TRANSACTION_CREATE';

    protected function getEntityClass(): string { return Transaction::class; }
    protected function getAttributePolicyMap(): array
    {
        return [
            self::VIEW => AccessPolicyEnum::PUBLIC,
            self::CREATE => AccessPolicyEnum::PUBLIC,
            self::EDIT => AccessPolicyEnum::OWNER,
        ];
    }
    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
