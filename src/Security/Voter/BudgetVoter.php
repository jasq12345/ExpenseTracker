<?php

namespace App\Security\Voter;

use App\Entity\Budget;
use App\Entity\User;
use App\Enum\AccessPolicyEnum;

final class BudgetVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'BUDGET_VIEW';
    public const string CREATE = 'BUDGET_CREATE';

    protected function getEntityClass(): string { return Budget::class; }
    protected function getAttributePolicyMap(): array
    {
        return [
            self::VIEW => AccessPolicyEnum::PUBLIC,
            self::CREATE => AccessPolicyEnum::PUBLIC,
        ];
    }


    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
