<?php

namespace App\Validator\Constraint\Budget;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class BudgetPolicyThresholdConsistency extends Constraint
{
    public string $message = 'Invalid data.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return BudgetPolicyThresholdConsistencyValidator::class;
    }
}

