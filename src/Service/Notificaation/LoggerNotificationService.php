<?php

namespace App\Service\Notificaation;

use App\Entity\User;
use Psr\Log\LoggerInterface;

readonly class LoggerNotificationService implements NotificationServiceInterface
{

    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function notify(User $user, string $message): void
    {
        $this->logger->info('Notification for user {user}: {message}', [
            'user' => $user->getEmail(),
            'message' => $message,
        ]);
    }
}
