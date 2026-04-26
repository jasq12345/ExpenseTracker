<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Transaction\ListTransactionDto;
use App\Entity\User;
use App\Repository\TransactionRepository;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TransactionPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private TransactionRepository $repository,
    ) {}

    public function items(User $user, PaginatedDtoInterface $dto): array
    {
        if (!$dto instanceof ListTransactionDto) {
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
        try {
            return $this->repository->countByUser($user);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

}
