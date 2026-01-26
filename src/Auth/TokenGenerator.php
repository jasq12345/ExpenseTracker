<?php

namespace App\Auth;

use App\Auth\Exception\TokenGenerationException;
use App\Entity\RefreshToken;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Random\RandomException;

/**
 * Generates authentication tokens for users.
 *
 * Creates both refresh tokens (long-lived, stored in database) and
 * access tokens (short-lived JWTs). Refresh tokens are generated using
 * cryptographically secure random bytes.
 *
 * @see RefreshToken Entity representing a stored refresh token
 */
readonly class TokenGenerator
{
    /**
     * @param JWTTokenManagerInterface $jwtManager Lexik JWT manager for access token creation
     */
    public function __construct(private JWTTokenManagerInterface $jwtManager) {}

    /**
     * Create a new RefreshToken entity for a user.
     *
     * Generates a 128-character hexadecimal token using cryptographically
     * secure random bytes. The token is associated with the user but
     * NOT persisted—caller must persist and flush.
     *
     * @param User $user The user to create a refresh token for
     *
     * @return RefreshToken New refresh token entity (not yet persisted)
     *
     * @throws TokenGenerationException When secure random byte generation fails
     */
    public function createRefreshToken(User $user): RefreshToken
    {
        try {
            $refreshToken = new RefreshToken();
            $refreshToken->setToken(bin2hex(random_bytes(64)));
            $refreshToken->setUser($user);

            return $refreshToken;
        } catch (RandomException) {
            throw new TokenGenerationException();
        }
    }

    /**
     * Create a JWT access token for a user.
     *
     * Generates a signed JWT containing user claims using the configured
     * Lexik JWT bundle settings (algorithm, TTL, etc.).
     *
     * @param User $user The user to create an access token for
     *
     * @return string Encoded JWT access token
     */
    public function createAccessToken(User $user): string
    {
        return $this->jwtManager->create($user);
    }
}
