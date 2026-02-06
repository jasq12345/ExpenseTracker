<?php

namespace App\Security\Voter;

use App\Entity\Transaction;
use App\Entity\User;

final class TransactionVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'TRANSACTION_VIEW';
    public const string EDIT = 'TRANSACTION_EDIT';
    public const string DELETE = 'TRANSACTION_DELETE';
    public const string CREATE = 'TRANSACTION_CREATE';

    protected function getEntityClass(): string { return Transaction::class; }
    protected function getViewAttribute(): string { return self::VIEW; }
    protected function getEditAttribute(): string { return self::EDIT; }
    protected function getDeleteAttribute(): string { return self::DELETE; }
    protected function getCreateAttribute(): string { return self::CREATE; }

    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
