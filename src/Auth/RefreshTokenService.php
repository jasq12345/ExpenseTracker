<?php

namespace App\Auth;

use App\Auth\Exception\RefreshTokenExpiredException;
use App\Auth\Exception\RefreshTokenNotFoundException;
use App\Entity\User;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service responsible for validating and removing refresh tokens.
 *
 * Handles checking if a refresh token exists, whether it has expired,
 * and removing it from the database. Intended to be used in workflows
 * that rotate refresh tokens or revoke them.
 */
readonly class RefreshTokenService
{
    public function __construct(
        public RefreshTokenRepository  $refreshTokenRepository,
        private EntityManagerInterface $em,
    ) {}

    /**
     * Validates a refresh token string and removes it from the database.
     *
     * @param string $refreshTokenData The refresh token string to validate and remove
     *
     * @throws RefreshTokenNotFoundException If the refresh token is not found
     * @throws RefreshTokenExpiredException If the refresh token has expired
     */
    public function validateAndRemoveToken(string $refreshTokenData): User
    {
        $token = $this->refreshTokenRepository->findOneBy(['token' => $refreshTokenData]);

        if (!$token) {
            throw new RefreshTokenNotFoundException();
        }

        $user = $token->getUser();

        $isExpired = $token->isExpired();

        // Remove the token from the entity manager
        $this->em->remove($token);

        // If expired, flush immediately and throw
        if ($isExpired) {
            $this->em->flush();
            throw new RefreshTokenExpiredException();
        }

        return $user;
    }
}
