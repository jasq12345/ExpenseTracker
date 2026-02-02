<?php

namespace App\Mapper;

use App\Dto\Auth\RegisterDto;
use App\Validator\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

readonly class RegistrationMapper
{
    public function __construct(
        private RequestValidator $requestValidator
    ) {}
    public function mapRequestToDto(Request $request): RegisterDto
    {
        $data = $this->requestValidator->decodeJson($request);

        return new RegisterDto(
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? ''
        );
    }
}
