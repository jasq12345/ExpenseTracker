<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginationDto;
use App\Entity\User;
use App\Repository\BudgetRepository;

readonly class BudgetPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private BudgetRepository $repository,
    ) {}
    public function items(User $user, PaginationDto $dto): array
    {
        return $this->repository->listByUser($user, $dto);
    }

    public function total(user $user): int
    {
        return $this->repository->countByUser($user);
    }
}
