<?php

namespace App\Provider\Pagination;

use App\Dto\Budget\ListBudgetDto;
use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;
use App\Repository\BudgetRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

readonly class BudgetPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private BudgetRepository $repository,
    ) {}

    public function items(User $user, PaginatedDtoInterface $dto): array
    {
        if (!$dto instanceof ListBudgetDto) {
            throw new \InvalidArgumentException('Expected ListBudgetDto.');
        }
        try {
            return $this->repository->listByUser($user, $dto);
        } catch (Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

    public function total(User $user, PaginatedDtoInterface $dto): int
    {
        try {
            return $this->repository->countByUser($user);
        } catch (Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }
}
