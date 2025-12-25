<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends GenericApiController
{
    public function __construct(UserRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct(User::class, $categoryRepository, $entityManager);
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

    public function newUser(Request $request, PostRequestValidator $validation): JsonResponse
    {
        return parent::newEntity($request, $validation);
    }
}
