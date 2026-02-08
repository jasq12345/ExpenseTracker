<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionDescriptionValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionDescription) {
            throw new UnexpectedTypeException($constraint, TransactionDescription::class);
        }

        // nullable — skip if null
        if ($value === null) {
            return;
        }

        if (mb_strlen($value) > 255) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
