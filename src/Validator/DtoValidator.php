<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class DtoValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(object $dto): ?JsonResponse
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) === 0) {
            return null;
        }

        $errors = [];
        $hasConflict = false;

        foreach ($violations as $violation) {
            $path = trim($violation->getPropertyPath(), '.');

            $errors[$path][] = $violation->getMessage();

            if ($violation->getMessage() === 'Conflict'){
                $hasConflict = true;
            }
        }

        return new JsonResponse(
            ['errors' => $errors],
            $hasConflict ? 409 : 400
        );
    }
}
