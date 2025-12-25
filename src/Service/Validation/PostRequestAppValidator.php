<?php

namespace App\Service\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PostRequestAppValidator implements AppValidatorInterface
{
    public function __construct(private ValidatorInterface $validator){}

    public function validateData($data): array
    {
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $errorMessages;
        }
        return [];
    }
}
