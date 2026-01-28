<?php

namespace App\Mapper;

use App\Dto\Auth\RefreshTokenDto;
use App\Service\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

readonly class RefreshTokenMapper
{
    public function __construct(
        private RequestValidator $requestValidator
    ) {}
    public function mapRequestToDto(Request $request): RefreshTokenDto
    {
        $data = $this->requestValidator->decodeJson($request);

        return new RefreshTokenDto($data['refreshToken'] ?? '');
    }
}
