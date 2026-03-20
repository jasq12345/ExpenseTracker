<?php

namespace App\Service\Notification;

use App\Entity\User;

interface NotificationServiceInterface
{
    public function notify(User $user, string $message): void;
}
