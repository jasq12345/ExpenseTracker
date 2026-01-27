<?php

namespace App\Service\Auth;

use App\Service\Validation\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param RequestValidator       $validator              Service for JSON request validation
     * @param EntityManagerInterface $em                     Doctrine entity manager for transactions
     */
    public function __construct(
        private TokenGenerator         $tokenGenerator,
        private RefreshTokenService    $refreshTokenService,
        private RequestValidator       $validator,
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
     * @param Request $request HTTP request containing JSON body with 'refreshToken' field
     *
     * @return array{refreshToken: string, accessToken: string} New token pair
     *
     * @throws \InvalidArgumentException     When request JSON is invalid or missing required fields
     */
    public function rotateRefreshToken(Request $request): array
    {
        $data = $this->validator->decodeJson($request, ['refreshToken']);

        return $this->em->wrapInTransaction(
            function () use ($data) {
            $user = $this->refreshTokenService->validateToken($data['refreshToken']);

            $newRefreshToken = $this->tokenGenerator->createRefreshToken($user);
            $accessToken = $this->tokenGenerator->createAccessToken($user);

            $this->refreshTokenService->removeToken($data['refreshToken']);
            $this->em->persist($newRefreshToken);

            return [
                'refreshToken' => $newRefreshToken->getToken(),
                'accessToken'  => $accessToken,
            ];
        });
    }
}
