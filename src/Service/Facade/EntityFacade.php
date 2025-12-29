<?php
namespace App\Service\Facade;

use App\Entity\User;
use App\Service\EntityDeletionService;
use App\Service\EntityHydrationService;
use App\Service\EntityMetadataService;
use App\Service\Validation\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;

class EntityFacade
{
    private array $entityNameCache = [];
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EntityHydrationService $hydration,
        private readonly UserValidator          $validator,
        private readonly EntityMetadataService  $metadataService,
        private readonly EntityDeletionService  $deletionService
    ) {}

    public function create(string $entityClass): object
    {
        if (!class_exists($entityClass)) {
            throw new \LogicException(sprintf(
                'Entity class "%s" does not exist',
                $entityClass
            ));
        }

        return new $entityClass();
    }

    public function getEntityName(object|string $entity): string
    {
        $class = is_object($entity) ? $entity::class : $entity;

        return $this->entityNameCache[$class]
            ??= $this->metadataService->getShortName($class);
    }

    public function decodeAndMutate(Request $request, object $entity): array
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        try {
            $this->hydration->hydrate($entity, $data);
        } catch (ORMException $e) {
            return ['error' => $e->getMessage()];
        }

        if ($entity instanceof User && isset($data['password'])){
            $entity->setPassword(
                $this->validator->hashPassword($data['password'], $entity)
            );
        }

        return $this->validator->validateData($entity);
    }

    public function persist(object $entity, bool $flush = true): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }


    public function flush(): void
    {
        $this->em->flush();
    }

    public function delete(object $entity, bool $flush = true): void
    {
        $this->deletionService->deleteRelations($entity);
        $this->em->remove($entity);
        if ($flush) {
            $this->em->flush();
        }
    }
}
