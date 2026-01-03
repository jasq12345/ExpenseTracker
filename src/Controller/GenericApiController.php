<?php

namespace App\Controller;

use App\Service\EntityFacade;
use App\Service\Factory\ApiErrorResponseFactory;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class GenericApiController extends AbstractController
{
    public function __construct(
        protected string $entityClass,
        protected ObjectRepository $repository,
        protected EntityFacade $facade,
        protected ApiErrorResponseFactory $errorResponseFactory
    ) {}

    abstract protected function getReadGroup(): string;

    //need to add authentication next, now im waiting to php 8.4 to compile co I can install lexic JWT
    protected function getAllEntities(): JsonResponse
    {
        $entities = $this->repository->findBy([], null, 50);
        return $this->json($entities, 200, [], [
            'groups' => [$this->getReadGroup()],
        ]);
    }

    protected function getOneEntity(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);

        if (!$entity) {
            return $this->errorResponseFactory
                ->notFound($this->facade->getEntityName($this->entityClass));
        }

        return $this->json($entity, 200, [], [
            'groups' => [$this->getReadGroup()],
        ]);
    }

    protected function deleteEntity(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);

        if (!$entity) {
            return $this->errorResponseFactory
                ->notFound($this->facade->getEntityName($this->entityClass));
        }

        try {
            $this->facade->delete($entity);
        } catch (Exception $e) {
            return $this->errorResponseFactory
                ->notSaved($this->facade->getEntityName($entity));
        }

        return $this->errorResponseFactory->success();
    }

    protected function newEntity(Request $request): JsonResponse
    {
        $entity = $this->facade->create($this->entityClass);

        try {
            $errors = $this->facade->decodeAndMutate($request, $entity);
            if (!empty($errors)) {
                return $this->errorResponseFactory->validation($errors);
            }
        } catch (InvalidArgumentException) {
            return $this->errorResponseFactory->invalidJson();
        }

        try {
            $this->facade->persist($entity);
        } catch (Exception) {
            return $this->errorResponseFactory
                ->notSaved($this->facade->getEntityName($this->facade->getEntityName($entity)));
        }

        return $this->errorResponseFactory->success();
    }

    protected function updateEntity(Request $request, int $id): JsonResponse
    {
        $entity = $this->repository->find($id);

        if (!$entity) {
            return $this->errorResponseFactory
                ->notFound($this->facade->getEntityName($this->entityClass));
        }

        try {
            $errors = $this->facade->decodeAndMutate($request, $entity);
            if (!empty($errors)) {
                return $this->errorResponseFactory->validation($errors);
            }
        } catch (InvalidArgumentException) {
            return $this->errorResponseFactory->invalidJson();
        }

        try {
            $this->facade->flush();
        } catch (Exception) {
            return $this->errorResponseFactory
                ->notSaved($this->facade->getEntityName($entity));
        }

        return $this->errorResponseFactory->success();
    }
}

