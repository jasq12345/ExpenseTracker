<?php

namespace App\Auth;

use App\Auth\Exception\RefreshTokenExpiredException;
use App\Auth\Exception\RefreshTokenNotFoundException;
use App\Auth\Exception\TokenGenerationException;
use App\Service\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Manages refresh token rotation and validation.
 */
readonly class RefreshTokenManager
{
    public function __construct(
        private TokenGenerator      $tokenGenerator,
        private RefreshTokenService $refreshTokenService,
        private RequestValidator    $validator
    ) {}

    /**
     * Rotates a refresh token by validating and removing the old one, then generating new tokens.
     *
     * @param Request $request The HTTP request containing the refresh token in JSON body
     *
     * @return array{refreshToken: string, accessToken: string} The new refresh and access tokens
     *
     * @throws RefreshTokenExpiredException If the refresh token has expired
     * @throws RefreshTokenNotFoundException If the refresh token is not found
     * @throws TokenGenerationException If secure token generation fails
     */
    public function rotateRefreshToken(Request $request): array
    {
        $data = $this->validator->decodeJson($request, ['refreshToken']);

        $user = $this->refreshTokenService->validateAndRemoveToken($data['refreshToken']);

        $refreshToken = $this->tokenGenerator->createRefreshToken($user);

        $accessToken = $this->tokenGenerator->createAccessToken($user);

        return ['refreshToken' => $refreshToken, 'accessToken' => $accessToken];
    }
}
