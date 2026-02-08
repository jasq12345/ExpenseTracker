<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionAmountValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionAmount) {
            throw new UnexpectedTypeException($constraint, TransactionAmount::class);
        }

        if ($value === null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (!is_int($value) || $value < 1) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
