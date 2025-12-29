<?php

namespace App\Service\Facade;

use App\Entity\Category;
use App\Service\EntityDeletionService;
use App\Service\EntityHydrationService;
use App\Service\EntityMetadataService;
use App\Service\Validation\UserValidator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryFacade extends EntityFacade
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EntityMetadataService $metadataService,
        private readonly EntityHydrationService $hydration,
        private readonly UserValidator $validator,
        private readonly EntityDeletionService $deletionService
    ) {
        parent::__construct($this->em, $this->hydration, $this->validator, $this->metadataService, $this->deletionService);
    }

    public function deleteCategory(Category $category, bool $flush = true): void
    {
        $user = $category->getUser();

        $user->removeCategory($category);

        if ($flush) {
            $this->em->flush();
        }
    }
}
