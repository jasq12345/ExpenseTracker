<?php

namespace App\Validator\Constraint\Pagination;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PaginationLimitValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PaginationLimit) {
            throw new UnexpectedTypeException($constraint, PaginationLimit::class);
        }

        if ($value === null) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return;
        }

        if (!is_int($value) || $value < 1 || $value > 100) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
