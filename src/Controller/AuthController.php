<?php

namespace App\Controller;

use App\Exception\Auth\TokenGenerationException;
use App\Mapper\LoginMapper;
use App\Mapper\RefreshTokenMapper;
use App\Mapper\UserRegistrationMapper;
use App\Service\Auth\LoginService;
use App\Service\Auth\RefreshTokenManager;
use App\Service\Auth\RegistrationService;
use App\Validator\DtoValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{

    public function __construct(
        private readonly UserRegistrationMapper $registrationMapper,
        private readonly RefreshTokenMapper     $refreshTokenMapper,
        private readonly LoginMapper            $loginMapper,
        private readonly LoginService           $loginService,
        private readonly RegistrationService    $registrationService,
        private readonly DtoValidator           $validator,
        private readonly RefreshTokenManager    $refreshTokenManager,
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

        $data = $this->refreshTokenManager->rotateRefreshToken($dto);


        return $this->json($data);
    }

    /**
     * @throws TokenGenerationException
     */
    #[Route('/auth/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $dto = $this->loginMapper->mapRequestToDto($request);

        if($response = $this->validator->validate($dto)) {
            return $response;
        }


        return $this->loginService->login($dto);
    }
}
