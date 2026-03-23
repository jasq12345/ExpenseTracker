<?php

namespace App\Validator\Constraint\Budget;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class BudgetWarningThreshold extends Constraint
{
    public string $message = 'Invalid data.';

    public function validatedBy(): string
    {
        return BudgetWarningThresholdValidator::class;
    }
}

