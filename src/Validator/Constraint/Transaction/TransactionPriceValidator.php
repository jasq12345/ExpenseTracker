<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionPriceValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionPrice) {
            throw new UnexpectedTypeException($constraint, TransactionPrice::class);
        }

        if ($value === null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (!is_numeric($value) || $value <= 0) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
