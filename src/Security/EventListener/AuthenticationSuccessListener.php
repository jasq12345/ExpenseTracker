<?php

namespace App\Security\EventListener;

use App\Entity\User;
use App\Exception\Auth\TokenGenerationException;
use App\Security\Token\RefreshTokenService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccess')]
readonly class AuthenticationSuccessListener
{
    public function __construct(
        private RefreshTokenService $refreshTokenService,
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

        // Safety check: ensure the token exists before manipulating it
        if (!isset($data['token'])) {
            return;
        }

        $refreshToken = $this->refreshTokenService->createRefreshToken($user);


        $data['refreshToken'] = $refreshToken->getToken();
        $data['accessToken'] = $data['token'];

        unset($data['token']);

        $event->setData($data);
    }
}
