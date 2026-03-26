<?php

namespace App\Security\EventListener;

use App\Entity\User;
use App\Exception\Auth\TokenGenerationException;
use App\Repository\RefreshTokenRepository;
use App\Security\Token\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccess')]
readonly class AuthenticationSuccessListener
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
        private RefreshTokenRepository $refreshTokenRepository,
    ) {}

    /**
     * @throws TokenGenerationException
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        if (!isset($data['token'])) {
            return;
        }

        $activeTokens = $this->refreshTokenRepository->findActiveByUser($user);
        if (count($activeTokens) >= 5) {
            $oldest = $activeTokens[0];
            $this->refreshTokenService->removeToken($user, $oldest);
        }

        $refreshToken = $this->refreshTokenService->createRefreshToken($user);

        $data['refreshToken'] = $refreshToken->getToken();
        $data['accessToken'] = $data['token'];
        unset($data['token']);

        $event->setData($data);
    }
}
