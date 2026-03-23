<?php

namespace App\Validator\Constraint\Budget;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BudgetWarningThresholdValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof BudgetWarningThreshold) {
            throw new UnexpectedTypeException($constraint, BudgetWarningThreshold::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_int($value) || $value < 0 || $value > 100) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

