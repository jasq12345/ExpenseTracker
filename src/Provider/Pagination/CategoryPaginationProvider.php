<?php

namespace App\Provider\Pagination;

use App\Dto\Category\ListCategoryDto;
use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;
use App\Repository\CategoryRepository;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class CategoryPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private readonly CategoryRepository $repository,
    ) {}

    public function items(User $user, PaginatedDtoInterface $dto): array
    {
        if (!$dto instanceof ListCategoryDto) {
            throw new InvalidArgumentException('Expected ListTransactionDto.');
        }

        try {
            return $this->repository->listByUser($user, $dto);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

    public function total(User $user, PaginatedDtoInterface $dto): int
    {
        if (!$dto instanceof ListCategoryDto) {
            throw new InvalidArgumentException('Expected ListTransactionDto.');
        }

        try {
            return $this->repository->countByUser($user);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }
}
