<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class DtoValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    /**
     * Validate a DTO and return a JsonResponse on error.
     * Returns null if DTO is valid.
     */
    public function validate(object $dto): ?JsonResponse
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            $statusCode = 400; // default

            foreach ($violations as $violation) {
                $path = $violation->getPropertyPath();
                $msg = $violation->getMessage();
                $errors[$path][] = $msg;

                if ($msg === 'Conflict') {
                    $statusCode = 409; // duplicate username
                }
            }

            return new JsonResponse(['errors' => $errors], $statusCode);
        }
        return null;
    }
}
