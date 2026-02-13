<?php

namespace App\Service;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserProviderService
{
    public function __construct(
        private Security $security,
    ) {}

    public function getUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new LogicException('User not authenticated.');
        }
        return $user;
    }

    public function getUserOrNull(): ?User
    {
        $user = $this->security->getUser();
        return $user instanceof User ? $user : null;
    }
}
