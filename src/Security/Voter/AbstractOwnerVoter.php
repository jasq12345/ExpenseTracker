<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractOwnerVoter extends Voter
{
    abstract protected function getEntityClass(): string;
    abstract protected function getOwner(mixed $subject): ?User;
    abstract protected function getViewAttribute(): string;
    abstract protected function getEditAttribute(): string;
    abstract protected function getDeleteAttribute(): string;
    abstract protected function getCreateAttribute(): string;

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === $this->getCreateAttribute()) {
            return true;
        }

        return in_array($attribute, [
                $this->getViewAttribute(),
                $this->getEditAttribute(),
                $this->getDeleteAttribute(),
            ], true) && is_a($subject, $this->getEntityClass());
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($attribute === $this->getCreateAttribute()) {
            return true;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        return match ($attribute) {
            $this->getViewAttribute() => true,
            $this->getEditAttribute(), $this->getDeleteAttribute() => $this->getOwner($subject) === $user,
            default => false,
        };
    }

    protected function isAdmin(User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
