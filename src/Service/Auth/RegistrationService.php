<?php

namespace App\Service\Auth;

use App\Entity\User;
use App\Service\Hydration\EntityHydrationService;
use App\Service\Validation\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\HttpFoundation\Request;

readonly class RegistrationService
{
    public function __construct(
        private RequestValidator       $requestValidator,
        private EntityHydrationService $hydrationService,
        private EntityManagerInterface $em
    ) {}

    /**
     * @throws MappingException
     * @throws ORMException
     */
    public function createNewUser(Request $request): void
    {
        $user = new User();

        $data = $this->requestValidator->decodeJson($request, ['username', 'email', 'password']);

        $this->hydrationService->hydrate($user, $data);

        $user->setPassword($this->validator->hashPassword($data['password'], $user));

        $this->em->persist($user);
        $this->em->flush();
    }
}
