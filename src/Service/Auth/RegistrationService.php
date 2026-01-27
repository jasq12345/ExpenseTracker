<?php

namespace App\Service\Auth;

use App\Entity\User;
use App\Service\Validation\RequestValidator;
use Symfony\Component\HttpFoundation\Request;

class RegistrationService
{
    public function __construct(
        private readonly RequestValidator $requestValidator,
    ) {}

    public function createNewUser(Request $request): void
    {
        $user = new User();

        // now decode request
        $data = $this->requestValidator->decodeJson($request, ['username', 'email', 'password']);
        //here hydrate properties form request to user entity


    }
}
