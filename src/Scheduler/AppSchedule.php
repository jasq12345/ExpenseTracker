<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('default')]
class AppSchedule implements ScheduleProviderInterface
{

    public function getSchedule(): Schedule
    {
        $schedule = new Schedule();

        // Schedule the CleanExpiredRefreshTokens message to run every hour
        $schedule->add(RecurringMessage::cron('0 2 * * *', new Message\CleanExpiredRefreshTokens()));

        return $schedule;
    }
}
