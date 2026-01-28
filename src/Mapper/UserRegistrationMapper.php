<?php

namespace App\Mapper;

use App\Dto\Auth\UserRegistrationDto;
use App\Service\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

readonly class UserRegistrationMapper
{
    public function __construct(
        private RequestValidator $requestValidator
    ) {}
    public function mapRequestToDto(Request $request): UserRegistrationDto
    {
        $data = $this->requestValidator->decodeJson($request);

        return new UserRegistrationDto(
            $data['username'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? ''
        );
    }
}
