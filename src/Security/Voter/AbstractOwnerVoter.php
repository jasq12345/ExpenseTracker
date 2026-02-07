<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\AccessPolicyEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Base voter that authorizes actions based on an attribute → policy map.
 *
 * Subclasses declare which attributes they handle and whether each one
 * is {@see AccessPolicyEnum::PUBLIC} (any authenticated user) or
 * {@see AccessPolicyEnum::OWNER} (only the resource owner or an admin).
 */
abstract class AbstractOwnerVoter extends Voter
{
    abstract protected function getEntityClass(): string;

    abstract protected function getOwner(mixed $subject): ?User;

    /**
     * Return a map of supported attribute constants to their access policy.
     *
     * Example:
     *   [
     *       self::VIEW => AccessPolicyEnum::PUBLIC,
     *       self::EDIT => AccessPolicyEnum::OWNER,
     *       self::DELETE => AccessPolicyEnum::OWNER,
     *   ]
     *
     * @return array<string, AccessPolicyEnum>
     */
    abstract protected function getAttributePolicyMap(): array;

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!array_key_exists($attribute, $this->getAttributePolicyMap())) {
            return false;
        }

        // For "create"-style attributes the subject may be null or a class string.
        if ($subject === null || (is_string($subject) && is_a($subject, $this->getEntityClass(), true))) {
            return true;
        }

        return is_a($subject, $this->getEntityClass());
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->isAdmin($user)) {
            return true;
        }

        $policy = $this->getAttributePolicyMap()[$attribute];

        return match ($policy) {
            AccessPolicyEnum::PUBLIC => true,
            AccessPolicyEnum::OWNER => $this->isOwner($subject, $user),
        };
    }

    private function isOwner(mixed $subject, User $user): bool
    {
        if (!is_object($subject)) {
            return false;
        }

        return $this->getOwner($subject)?->getId() === $user->getId();
    }

    protected function isAdmin(User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
