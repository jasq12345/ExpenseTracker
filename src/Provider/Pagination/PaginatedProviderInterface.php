<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;

interface PaginatedProviderInterface
{
    public function items(User $user, PaginatedDtoInterface $dto): array;

    public function total(User $user, PaginatedDtoInterface $dto): int;
}
