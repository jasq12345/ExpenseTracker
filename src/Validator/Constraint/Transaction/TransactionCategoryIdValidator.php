<?php

namespace App\Validator\Constraint\Transaction;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TransactionCategoryIdValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TransactionCategoryId) {
            throw new UnexpectedTypeException($constraint, TransactionCategoryId::class);
        }

        // nullable — skip if null
        if ($value === null) {
            return;
        }

        if (!is_int($value) || $value < 1) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        $category = $this->em->getRepository(Category::class)->find($value);

        if (!$category) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
