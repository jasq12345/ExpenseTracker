<?php

namespace App\Security\Voter;

use App\Entity\Category;
use App\Entity\User;
use App\Enum\AccessPolicyEnum;

final class CategoryVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'CATEGORY_VIEW';
    public const string EDIT = 'CATEGORY_EDIT';
    public const string DELETE = 'CATEGORY_DELETE';
    public const string CREATE = 'CATEGORY_CREATE';

    protected function getEntityClass(): string { return Category::class; }

    protected function getAttributePolicyMap(): array
    {
        return [
            self::VIEW => AccessPolicyEnum::PUBLIC,
            self::CREATE => AccessPolicyEnum::PUBLIC,
            self::EDIT => AccessPolicyEnum::OWNER,
            self::DELETE => AccessPolicyEnum::OWNER,
        ];
    }

    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
