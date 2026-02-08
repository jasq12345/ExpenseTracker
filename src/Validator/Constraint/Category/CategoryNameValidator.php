<?php

namespace App\Validator\Constraint\Category;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryNameValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryName) {
            throw new UnexpectedTypeException($constraint, CategoryName::class);
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
