<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route("/api/users", name: "app_users", methods: ["GET"])]
    public function getAllUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json($users);
    }

    #[Route("/api/user/{id}", name: "app_user_get", methods: ["GET"])]
    public function getOneUser(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if(!$user){
            return $this->json(["error" => "User not found"], 404);
        }
        return $this->json($user);
    }

    #[Route("api/user/{id}", name: "app_user_delete", methods: ["DELETE"])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if(!$user){
            return $this->json(["error" => "User not found"], 404);
        }

        try{
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $e){
            return $this->json(["error" => $e->getMessage()], 500);
        }

        return $this->json(['success' => true]);
    }


    public function newUser(Request $request, PostRequestValidator $validation, EntityManagerInterface $entityManager): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $errors = $validation->validateData($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $user = new User($data);

        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to save transaction'], 500);
        }

        return $this->json(['success' => true]);
    }
}
