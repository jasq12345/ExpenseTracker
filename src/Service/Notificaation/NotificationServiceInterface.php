<?php

namespace App\Service\Notificaation;

use App\Entity\User;

interface NotificationServiceInterface
{
    public function notify(User $user, string $message): void;
}
