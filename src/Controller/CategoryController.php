<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\Facade\CategoryFacade;
use App\Service\Facade\EntityFacade;
use App\Service\Factory\ApiErrorResponseFactory;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends GenericApiController
{
    protected function getReadGroup(): string
    {
        return 'category:read';
    }
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        protected EntityFacade              $facade,
        protected ApiErrorResponseFactory             $errorResponseFactory,
        private readonly CategoryFacade $categoryFacade
    ) {
        parent::__construct(Category::class, $categoryRepository, $facade, $errorResponseFactory);
    }
    #[Route('/api/categories/', name: 'app_categories', methods: ["GET"])]
    public function getAllCategories(): JsonResponse
    {
        return parent::getAllEntities();
    }

    #[Route('/api/category/{id}', name: 'app_category_get', methods: ["GET"])]
    public function getOneCategory(int $id): JsonResponse
    {
        return parent::getOneEntity($id);
    }

    #[Route('/api/category/{id}', name: 'app_category_delete', methods: ["DELETE"])]
    public function deleteCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->errorResponseFactory
                ->notFound($this->categoryFacade->getEntityName($this->entityClass));
        }

        try {
            $this->categoryFacade->deleteCategory($category);
        } catch (Exception $e) {
            return $this->errorResponseFactory
                ->notSaved($this->facade->getEntityName($e->getMessage()));
        }

        return $this->errorResponseFactory->success();
    }

    // Later change to use method addCategory from User entity !!!!!
    // this can cause trouble with the owning side of the relation
    #[Route("/api/category/", name: "app_category_post", methods: ["POST"])]
    public function newCategory(Request $request): JsonResponse
    {
        return parent::newEntity($request);
    }

    #[Route("/api/category/{id}", name: "app_category_put", methods: ["PUT", "PATCH"])]
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        return parent::updateEntity($request, $id);
    }
}
