<?php

namespace App\Controller;

use App\Mapper\UserRegistrationMapper;
use App\Service\Auth\RegistrationService;
use App\Validator\DtoValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{

    public function __construct(
        private readonly UserRegistrationMapper $mapper,
        private readonly RegistrationService $registrationService,
        private readonly DtoValidator $validator
    ) {}
    #[Route('/auth/register', name: 'token')]
    public function register(Request $request): JsonResponse
    {
        $dto = $this->mapper->mapRequestToDto($request);

        if ($response = $this->validator->validate($dto)) {
            return $response;
        }

        $this->registrationService->createNewUser($dto);

        return $this->json(['message' => 'User created successfully']);
    }
}
