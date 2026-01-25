<?php

namespace App\Controller;

use App\Auth\Exception\RefreshTokenExpiredException;
use App\Auth\Exception\RefreshTokenNotFoundException;
use App\Auth\Exception\TokenGenerationException;
use App\Auth\RefreshTokenManager;
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
        try {
            $data = $this->refreshTokenManager->rotateRefreshToken($request);
        } catch (RefreshTokenExpiredException $e) {
            return $this->json(['error' => $e->getMessage()], 401);
        } catch (RefreshTokenNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (TokenGenerationException $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }


        return $this->json($data);
    }
}
