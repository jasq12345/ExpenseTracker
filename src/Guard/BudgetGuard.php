<?php

namespace App\Guard;

use App\Entity\Budget;
use App\Enum\BudgetPolicyEnum;
use App\Enum\BudgetThresholdEnum;

class BudgetGuard
{
    public function getThresholdStatus(Budget $budget): BudgetThresholdEnum
    {
        $policy = $budget->getBudgetPolicy();
        $spent = (float) ($budget->getSpentAmount() ?? 0);
        $limit = (float) ($budget->getLimitAmount() ?? 0);

        if (!$policy->getPolicy()->requiresWarningThreshold() || $limit <= 0) {
            return BudgetThresholdEnum::NORMAL;
        }

        $usedPercentage = ($spent / $limit) * 100;
        $threshold = $policy->getWarningThreshold();

        return match (true) {
            $usedPercentage > 100 => BudgetThresholdEnum::EXCEEDED,
            $usedPercentage >= 100 => BudgetThresholdEnum::LIMIT_REACHED,
            $usedPercentage >= $threshold => BudgetThresholdEnum::WARNING,
            default => BudgetThresholdEnum::NORMAL,
        };
    }

    public function canAddExpense(Budget $budget, float $amount): bool
    {
        $policy = $budget->getBudgetPolicy()->getPolicy();

        if (!$policy->requiresWarningThreshold()) {
            return true;
        }

        $newSpent = (float) ($budget->getSpentAmount() ?? 0) + $amount;
        $limit = (float) ($budget->getLimitAmount() ?? 0);

        return match ($policy) {
            BudgetPolicyEnum::STRICT => $newSpent <= $limit,
            default => true,
        };
    }
}
