<?php

namespace App\Controller;

use App\Mapper\RefreshTokenMapper;
use App\Service\Auth\RefreshTokenManager;
use App\Service\Validation\DtoValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RefreshTokenController extends AbstractController
{
    public function __construct(
        private readonly RefreshTokenManager $refreshTokenManager,
        private readonly RefreshTokenMapper $mapper,
        private readonly DtoValidator $validator
    ) {}

    #[Route('/refresh/token', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(Request $request): JsonResponse
    {
        $dto = $this->mapper->mapRequestToDto($request);

        if ($response = $this->validator->validate($dto)) {
            return $response;
        }

        $data = $this->refreshTokenManager->rotateRefreshToken($dto);

        return $this->json($data);
    }
}
