<?php

namespace App\Auth;

use App\Auth\Exception\TokenGenerationException;
use App\Entity\RefreshToken;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Random\RandomException;

readonly class TokenGenerator
{
    public function __construct(private JWTTokenManagerInterface $jwtManager) {}

    /**
     * Create a new RefreshToken entity for a given user.
     * @throws TokenGenerationException
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
     * Create a JWT access token for a given user.
     */
    public function createAccessToken(User $user): string
    {
        return $this->jwtManager->create($user);
    }
}
