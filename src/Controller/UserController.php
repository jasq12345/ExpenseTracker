<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EntityFacade;
use App\Service\Factory\ApiErrorResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends GenericApiController
{
    public function __construct(UserRepository $categoryRepository, EntityFacade $facade, ApiErrorResponseFactory $errorResponseFactory)
    {
        parent::__construct(User::class, $categoryRepository, $facade, $errorResponseFactory);
    }
    #[Route("/api/users", name: "app_users", methods: ["GET"])]
    public function getAllUsers(): JsonResponse
    {
        return parent::getAllEntities();
    }

    #[Route("/api/user/{id}", name: "app_user_get", methods: ["GET"])]
    public function getOneUser(int $id): JsonResponse
    {
        return parent::getOneEntity($id);
    }

    #[Route("api/user/{id}", name: "app_user_delete", methods: ["DELETE"])]
    public function deleteUser(int $id): JsonResponse
    {
        return parent::deleteEntity($id);
    }

    #[Route("/api/user", name: "app_user_post", methods: ["POST"])]
    public function newUser(Request $request): JsonResponse
    {
        return parent::newEntity($request);
    }

    #[Route("/api/user/{id}", name: "app_user_put", methods: ["PUT", "PATCH"])]
    public function updateUser(Request $request, int $id): JsonResponse
    {
        return parent::updateEntity($request, $id);
    }
}
