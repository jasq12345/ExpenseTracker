<?php

namespace App\Service\Notificaation;

use App\Entity\Budget;
use App\Enum\BudgetThresholdEnum;
use App\Guard\BudgetGuard;

readonly class BudgetAlertService
{
    public function __construct(
        private BudgetGuard $budgetGuard,
        private LoggerNotificationService $notificationService
    ) {}

    public function checkAndAlert(Budget $budget): void
    {
        $status = $this->budgetGuard->getThresholdStatus($budget);

        if ($status === BudgetThresholdEnum::NORMAL) {
            return;
        }

        $this->sendAlert($budget, $status);
    }

    private function sendAlert(Budget $budget, BudgetThresholdEnum $status): void
    {
        $message = match ($status) {
            BudgetThresholdEnum::WARNING => sprintf(
                'Uwaga! Wykorzystałeś "%s" swojego budżetu.',
                $this->getUsedPercentage($budget),
            ),
            BudgetThresholdEnum::LIMIT_REACHED => 'Osiągnąłeś limit budżetu!',
            BudgetThresholdEnum::EXCEEDED => 'Przekroczyłeś budżet !',
            default => null,
        };

        if ($message) {
            $this->notificationService->notify($budget->getUser(), $message);
        }
    }

    private function getUsedPercentage(Budget $budget): string
    {
        $spent = (float) ($budget->getSpentAmount() ?? 0);
        $limit = (float) ($budget->getLimitAmount() ?? 1);

        return number_format(($spent / $limit) * 100);
    }
}
