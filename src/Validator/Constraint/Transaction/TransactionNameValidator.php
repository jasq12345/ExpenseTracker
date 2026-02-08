<?php

namespace App\Validator\Constraint\Transaction;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionNameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionName) {
            throw new UnexpectedTypeException($constraint, TransactionName::class);
        }

        if (!$value || trim((string) $value) === '') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (mb_strlen($value) < 2 || mb_strlen($value) > 100) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
