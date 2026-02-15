<?php

namespace App\Service;

use App\Entity\User;

interface NotificationServiceInterface
{
    public function notify(User $user, string $message): void;
}
