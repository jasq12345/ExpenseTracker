<?php

namespace App\Controller;

use App\Service\Auth\RefreshTokenManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RefreshTokenController extends AbstractController
{
    public function __construct(
        private readonly RefreshTokenManager $refreshTokenManager,
    ) {}

    #[Route('/refresh/token', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(Request $request): JsonResponse
    {
        $data = $this->refreshTokenManager->rotateRefreshToken($request);
        return $this->json($data);
    }
}
