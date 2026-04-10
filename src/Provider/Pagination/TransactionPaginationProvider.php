<?php

namespace App\Provider\Pagination;

use App\Dto\Transaction\ListTransactionDto;
use App\Entity\User;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TransactionPaginationProvider
{
    public function __construct(
        private TransactionRepository $repository,
    ) {}

    public function items(User $user, ListTransactionDto $dto): array
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
