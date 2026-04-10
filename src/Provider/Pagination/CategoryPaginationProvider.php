<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginationDto;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class CategoryPaginationProvider
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {}

    public function items(User $user, PaginationDto $dto): array
    {
        try {
            return $this->repository->listByUser($user, $dto);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

    public function total(User $user): int
    {
        try {
            return $this->repository->countByUser($user);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }
}
