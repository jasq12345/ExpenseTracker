<?php

namespace App\Controller;

use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class RefreshTokenController extends AbstractController
{
    public function __construct(
        readonly RefreshTokenRepository $refreshTokenRepository,
        private readonly EntityManagerInterface $em,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {}
    #[Route('/refresh/token', name: 'app_refresh_token', methods: ['POST'])]
    public function newRefreshToken(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !isset($data['oldRefreshToken'])) {
            return $this->json(['error' => 'Invalid request'], 400);
        }

        $oldRefreshToken = $this->refreshTokenRepository->findOneBy(['token' => $data['oldRefreshToken']]);

        if (!$oldRefreshToken) {
            return $this->json(['error' => 'Refresh token not found'], 404);
        }

        $expired = $oldRefreshToken->isExpired();

        //this must stay together, otherwise the flush will not work
        $this->em->remove($oldRefreshToken);
        if ($expired) {
            $this->em->flush();
            return $this->json(['error' => 'Refresh token expired'], 401);
        }

        $newRefreshToken = new RefreshToken();
        try{
            $newRefreshToken->setToken(bin2hex(random_bytes(64)));
        } catch (Exception){
            return $this->json(['error' => 'Error generating token'], 500);
        }
        $user = $oldRefreshToken->getUser();
        $newRefreshToken->setUser($user);
        // cos

        $this->em->persist($newRefreshToken);
        $this->em->flush();

        $accessToken = $this->jwtManager->create($user);

        return $this->json([
            'refreshToken' => $newRefreshToken->getToken(),
            'accessToken' => $accessToken
        ]);
    }
}
