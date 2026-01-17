<?php

namespace App\Controller;

use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RefreshTokenController extends AbstractController
{
    public function __construct(readonly RefreshTokenRepository $refreshTokenRepository, private EntityManagerInterface $em){
    }
    #[Route('/refresh/token', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['refreshToken'])) {
            return $this->json(['error' => 'Invalid request'], 400);
        }

        $refreshToken = $this->refreshTokenRepository->findOneBy(['token' => $data['refreshToken']]);

        if (!$refreshToken) {
            return $this->json(['error' => 'Refresh token not found'], 404);
        }

        $expired = $refreshToken->isExpired();

        $this->em->remove($refreshToken);

        //$this->em->persist($refreshToken);

        if ($expired) {
            return $this->json(['error' => 'Refresh token expired'], 401);
        }

        $this->em->flush();
        return $this->json([
            'token' => "token",
        ]);
    }
}
