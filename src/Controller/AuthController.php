<?php

namespace App\Controller;

use App\Mapper\RefreshTokenMapper;
use App\Mapper\RegistrationMapper;
use App\Security\Token\RefreshTokenService;
use App\Service\Auth\RegistrationService;
use App\Validator\DtoValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{

    public function __construct(
        private readonly RegistrationMapper  $registrationMapper,
        private readonly RefreshTokenMapper  $refreshTokenMapper,
        private readonly RegistrationService $registrationService,
        private readonly RefreshTokenService $refreshTokenService,
        private readonly DtoValidator        $validator,
    ) {}
    #[Route('/auth/register', name: 'token')]
    public function register(Request $request): JsonResponse
    {
        $dto = $this->registrationMapper->mapRequestToDto($request);

        if ($response = $this->validator->validate($dto)) {
            return $response;
        }

        $this->registrationService->createNewUser($dto);

        return $this->json(['message' => 'User created successfully'], 201);
    }

    #[Route('/refresh/token', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(Request $request): JsonResponse
    {
        $dto = $this->refreshTokenMapper->mapRequestToDto($request);

        if ($response = $this->validator->validate($dto)) {
            return $response;
        }

        $data = $this->refreshTokenService->rotateRefreshToken($dto);


        return $this->json($data);
    }

    #[Route('/auth/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): never
    {
        throw new \LogicException('Handled by json_login firewall');
    }
}
