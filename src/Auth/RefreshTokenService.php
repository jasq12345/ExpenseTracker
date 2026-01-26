<?php

namespace App\Auth;

use App\Entity\User;
use App\Exception\Auth\RefreshTokenExpiredException;
use App\Exception\Auth\RefreshTokenNotFoundException;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for refresh token validation and removal operations.
 *
 * Provides low-level operations for working with refresh tokens,
 * including validation checks and database removal. This service
 * does not handle persistence of new tokens or transaction management.
 *
 * @see RefreshTokenManager For high-level token rotation with transactions
 */
readonly class RefreshTokenService
{
    /**
     * @param RefreshTokenRepository $refreshTokenRepository Repository for refresh token queries
     * @param EntityManagerInterface $em                     Doctrine entity manager for removals
     */
    public function __construct(
        private RefreshTokenRepository  $refreshTokenRepository,
        private EntityManagerInterface $em,
    ) {}

    /**
     * Validate a refresh token without removing it.
     *
     * Checks that the token exists in the database and has not expired.
     * Does not modify the token or database state.
     *
     * @param string $refreshTokenData The raw refresh token string to validate
     *
     * @return User The user associated with the valid token
     *
     * @throws RefreshTokenNotFoundException When no token matches the provided string
     * @throws RefreshTokenExpiredException  When the token exists but has expired
     */
    public function validateToken(string $refreshTokenData): User
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $refreshTokenData]);

        if (!$token) {
            throw new RefreshTokenNotFoundException();
        }

        if ($token->isExpired()) {
            throw new RefreshTokenExpiredException();
        }

        return $token->getUser();
    }

    /**
     * Remove a refresh token from the database.
     *
     * Finds the token by its string value and schedules it for removal.
     * Also removes the token from the user's token collection.
     * Does nothing if the token does not exist.
     *
     * Note: Caller must flush the entity manager or wrap in a transaction
     * to persist the removal.
     *
     * @param string $refreshTokenData The raw refresh token string to remove
     *
     * @return void
     */
    public function removeToken(string $refreshTokenData): void
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $refreshTokenData]);

        if ($token) {
            $token->getUser()->removeRefreshToken($token);
            $this->em->remove($token);
        }
    }
}
