<?php

namespace App\Mapper;

use App\Dto\Auth\LoginDto;
use App\Validator\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

readonly class LoginMapper
{
    public function __construct(
        private RequestValidator $requestValidator
    ) {}
    public function mapRequestToDto(Request $request): LoginDto
    {
        $data = $this->requestValidator->decodeJson($request);

        $idenifier = $data['identifier'] ?? '';
        $password = $data['password'] ?? '';
        return new LoginDto(
            $idenifier ?? '',
            $password ?? ''
        );
    }
}
