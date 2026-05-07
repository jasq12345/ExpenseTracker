<?php

namespace App\Service\Notification;

use App\Entity\User;
use Predis\Client;

readonly class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private Client $redis
    ){}

    public function notify(User $user, string $message): void
    {
        $this->redis->publish('notifications', json_encode([
            'userId' => $user->getId(),
            'message' => $message,
        ]));
    }

    public function listen(User $user): void
    {
        $pubSub = $this->redis->pubSubLoop();
        $pubSub->subscribe('notifications.' . $user->getId());

        foreach ($pubSub as $message) {
            if ($message->kind === 'message') {
                echo "data: " . $message->payload . "\n\n";
                ob_flush();
                flush();
            }
        }
    }
}
