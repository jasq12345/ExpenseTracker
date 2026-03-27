<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginationDto;
use App\Entity\User;

interface PaginatedProviderInterface
{
    public function items(User $user, PaginationDto $dto): array;

    public function total(User $user): int;
}
