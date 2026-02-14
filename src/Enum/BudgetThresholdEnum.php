<?php

namespace App\Enum;

enum BudgetThresholdEnum: string
{
    case NORMAL = 'normal';
    case WARNING = 'warning';
    case LIMIT_REACHED = 'limit';
    case EXCEEDED = 'exceeded';

    public function shouldBlock(BudgetPolicyEnum $policy): bool
    {
        return match ($policy) {
            BudgetPolicyEnum::UNLIMITED, BudgetPolicyEnum::FLEXIBLE => $this === self::EXCEEDED,
            BudgetPolicyEnum::STRICT => $this === self::LIMIT_REACHED || $this === self::EXCEEDED,
        };
    }

    public function shouldWarn(BudgetPolicyEnum $policy): bool
    {
        if ($policy === BudgetPolicyEnum::UNLIMITED) {
            return false;
        }

        return $this === self::WARNING || $this === self::LIMIT_REACHED;
    }
}

