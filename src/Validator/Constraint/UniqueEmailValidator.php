<?php

namespace App\Validator\Constraint;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            return;
        }

        $value = trim((string) $value);

        if ($value === '') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        $existing = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email' => $value]);

        if ($existing) {
            $this->context->buildViolation($constraint->messageConflict)
                ->addViolation();
        }
    }
}
