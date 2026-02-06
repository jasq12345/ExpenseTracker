<?php

namespace App\Security\Voter;

use App\Entity\Budget;
use App\Entity\User;

final class BudgetVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'BUDGET_VIEW';
    public const string EDIT = 'BUDGET_EDIT';
    public const string DELETE = 'BUDGET_DELETE';
    public const string CREATE = 'BUDGET_CREATE';

    protected function getEntityClass(): string { return Budget::class; }
    protected function getViewAttribute(): string { return self::VIEW; }
    protected function getEditAttribute(): string { return self::EDIT; }
    protected function getDeleteAttribute(): string { return self::DELETE; }
    protected function getCreateAttribute(): string { return self::CREATE; }

    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
