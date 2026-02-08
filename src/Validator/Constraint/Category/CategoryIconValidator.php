<?php

namespace App\Validator\Constraint\Category;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryIconValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryIcon) {
            throw new UnexpectedTypeException($constraint, CategoryIcon::class);
        }

        // nullable — skip if null
        if ($value === null) {
            return;
        }

        if (mb_strlen($value) > 50) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
