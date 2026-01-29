<?php

namespace App\Validator\Constraint;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

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

        $value = trim((string)$value);

        if ($value === '') {
            $this->context->buildViolation($constraint->messageNotBlank)
                ->addViolation();
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->context->buildViolation($constraint->messageInvalid)
                ->addViolation();
            return;
        }

        $existing = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $value]);

        if ($existing) {
            $this->context->buildViolation($constraint->messageUnique)
                ->addViolation();
        }
    }
}
