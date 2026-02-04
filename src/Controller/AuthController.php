<?php

namespace App\Controller;

use App\Dto\Auth\RefreshTokenDto;
use App\Dto\Auth\RegisterDto;
use App\Security\Token\RefreshTokenService;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'token')]
    public function register(
        #[MapRequestPayload] RegisterDto $dto,
        RegistrationService $registrationService,
    ): JsonResponse
    {
        $registrationService->createNewUser($dto);

        return $this->json(['message' => 'User created successfully'], 201);
    }

    #[Route('/refresh', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(
        #[MapRequestPayload] RefreshTokenDto $dto,
        RefreshTokenService $refreshTokenService
    ): JsonResponse
    {
        $data = $refreshTokenService->rotateRefreshToken($dto);

        return $this->json($data);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(): never
    {
        throw new \LogicException('Handled by json_login firewall');
    }
}
