<?php

namespace App\Service\Validation;

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
        $errors = $this->validator->validate($dto);
        if (count($errors) === 0) {
            return null;
        }

        $messages = array_map(
            fn($e) => $e->getMessage(),
            iterator_to_array($errors)
        );

        return new JsonResponse(['errors' => $messages], 400);
    }
}
