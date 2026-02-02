<?php

namespace App\Service\Auth;

use App\Dto\Auth\RefreshTokenDto;
use App\Entity\User;
use App\Exception\Auth\TokenGenerationException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Manages refresh token rotation for user authentication.
 *
 * Handles the secure rotation of refresh tokens by validating existing tokens,
 * generating new token pairs, and persisting changes atomically within a database transaction.
 *
 * @see RefreshTokenService For token validation and removal operations
 * @see TokenGenerator For token creation logic
 */
readonly class RefreshTokenManager
{
    /**
     * @param TokenGenerator         $tokenGenerator         Service for creating new tokens
     * @param RefreshTokenService    $refreshTokenService    Service for validating/removing tokens
     * @param EntityManagerInterface $em                     Doctrine entity manager for transactions
     */
    public function __construct(
        private TokenGenerator         $tokenGenerator,
        private RefreshTokenService    $refreshTokenService,
        private EntityManagerInterface $em
    ) {}

    /**
     * Rotate a refresh token by invalidating the old one and issuing a new pair.
     *
     * This method performs the following steps atomically:
     * 1. Validates the incoming refresh token
     * 2. Generates new refresh and access tokens
     * 3. Removes the old refresh token
     * 4. Persists the new refresh token
     *
     * All operations occur within a database transaction to ensure consistency.
     * If token generation fails, the transaction rolls back and the old token remains valid.
     *
     * @param RefreshTokenDto $dto
     * @return array{refreshToken: string, accessToken: string} New token pair
     *
     */
    public function rotateRefreshToken(RefreshTokenDto $dto): array
    {
        return $this->em->wrapInTransaction(
            function () use ($dto) {
            $user = $this->refreshTokenService->validateToken($dto->refreshToken);

            list($newRefreshToken, $accessToken) = $this->newTokens($user);

            $this->refreshTokenService->removeToken($dto->refreshToken);
            $this->em->persist($newRefreshToken);

            return [
                'refreshToken' => $newRefreshToken->getToken(),
                'accessToken'  => $accessToken,
            ];
        });
    }

    /**
     * @param User $user
     * @return array
     * @throws TokenGenerationException
     */
    public function newTokens(User $user): array
    {
        $newRefreshToken = $this->tokenGenerator->createRefreshToken($user);
        $accessToken = $this->tokenGenerator->createAccessToken($user);
        return array($newRefreshToken, $accessToken);
    }
}
