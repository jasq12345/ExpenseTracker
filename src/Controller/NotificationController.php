<?php

namespace App\Controller;

use App\Service\Notification\NotificationService;
use App\Service\UserProviderService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/notifications')]
class NotificationController
{
    #[Route('/', name: 'notifications')]
    public function stream(
        NotificationService $notificationService,
        UserProviderService $userProvider
    ): StreamedResponse
    {
        $user = $userProvider->getUser();

        $response = new StreamedResponse(function () use ($notificationService, $user) {
            $notificationService->listen($user);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
