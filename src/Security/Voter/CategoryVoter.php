<?php

namespace App\Security\Voter;

use App\Entity\Category;
use App\Entity\User;

final class CategoryVoter extends AbstractOwnerVoter
{
    public const string VIEW = 'CATEGORY_VIEW';
    public const string EDIT = 'CATEGORY_EDIT';
    public const string DELETE = 'CATEGORY_DELETE';
    public const string CREATE = 'CATEGORY_CREATE';

    protected function getEntityClass(): string { return Category::class; }
    protected function getViewAttribute(): string { return self::VIEW; }
    protected function getEditAttribute(): string { return self::EDIT; }
    protected function getDeleteAttribute(): string { return self::DELETE; }
    protected function getCreateAttribute(): string { return self::CREATE; }

    protected function getOwner(mixed $subject): ?User
    {
        return $subject->getUser();
    }
}
