<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginationDto;
use App\Entity\User;
use App\Repository\CategoryRepository;
use DomainException;

readonly class CategoryPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private readonly CategoryRepository $repository,

    ) {}

    public function items(User $user, PaginationDto $dto): array
    {
        $categories = $this->repository->listByUser($user, $dto);

        if(!$categories){
            throw new DomainException('No budgets found.');
        }
        return $categories;
    }

    public function total(user $user): int
    {
        return $this->repository->countByUser($user);
    }
}
