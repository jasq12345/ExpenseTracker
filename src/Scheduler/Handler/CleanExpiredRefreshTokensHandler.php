<?php

namespace App\Scheduler\Handler;

use App\Exception\DomainException\RefreshTokenDeletionException;
use App\Repository\RefreshTokenRepository;
use App\Scheduler\Message\CleanExpiredRefreshTokens;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
readonly class CleanExpiredRefreshTokensHandler
{

    public function __construct(
        private RefreshTokenRepository $repository
    ){}

    public function __invoke(CleanExpiredRefreshTokens $message): void
    {
        try{
            $this->repository->deleteExpiredTokens();
        } catch (Throwable) {
            throw new RefreshTokenDeletionException('Failed to delete expired refresh tokens.');
        }
    }
}
