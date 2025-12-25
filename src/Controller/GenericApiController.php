<?php

namespace App\Controller;

use App\Service\Validation\AppValidatorInterface;
use App\Service\Validation\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

abstract class GenericApiController extends AbstractController
{
    protected string $entityClass;
    protected EntityRepository $repository;
    protected AppValidatorInterface $validator;

    public function __construct(string $entityClass, EntityRepository $repository,
                                private readonly EntityManagerInterface $entityManager,
                                AppValidatorInterface $validator)
    {
        $this->entityClass = $entityClass;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    #[Route("/api/{entity}", name: "app_entities", methods: ["GET"])]
    public function getAllEntities(): JsonResponse
    {
        $entities = $this->repository->findAll();
        return $this->json($entities);
    }

    #[Route("/api/{entity}/{id}", name: "app_entity_get", methods: ["GET"])]
    public function getOneEntity(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(["error" => "Entity not found"], 404);
        }
        return $this->json($entity);
    }

    #[Route('/api/{entity}/{id}', name: 'app_entity_delete', methods: ['DELETE'])]
    public function deleteEntity(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Transaction not found'], 404);
        }

        try {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/api/{entity}', name: 'app_entity_post', methods: ['POST'])]
    public function newEntity(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = new $this->entityClass($data);

        if($this->validator instanceof UserValidator && isset($data['password'])){
            $hashedPassword = $this->validator->hashPassword($data['password'], $entity);
            $entity->setPassword($hashedPassword);
        }

        $errors = $this->validator->validateData($entity);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Exception $e) {
            return $this->json(['error' => 'Failed to save transaction'], 500);
        }

        return $this->json(['success' => true]);
    }
}

