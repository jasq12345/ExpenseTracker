<?php

namespace App\Validator\Constraint\Budget;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BudgetLimitAmountValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof BudgetLimitAmount) {
            throw new UnexpectedTypeException($constraint, BudgetLimitAmount::class);
        }

        if ($value === null || trim((string) $value) === '') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (!is_numeric($value) || (float) $value <= 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

