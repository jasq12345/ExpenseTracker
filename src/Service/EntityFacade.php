<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EntityFacade
{
    private array $entityNameCache = [];
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MutationEntityService  $mutationService,
        private readonly EntityMetadataService $metadataService
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

        return $this->mutationService->mutateAndValidate($data, $entity);
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
        $this->em->remove($entity);
        if ($flush) {
            $this->em->flush();
        }
    }
}
