<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends GenericApiController
{
    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct(Category::class, $categoryRepository, $entityManager);
    }
    #[Route('/api/category', name: 'app_categories', methods: ["GET"])]
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
        return parent::deleteEntity($id);
    }

    #[Route("/api/category/", name: "app_category_post", methods: ["POST"])]
    public function newCategory(Request $request, PostRequestValidator $validation): JsonResponse
    {
        return parent::newEntity($request, $validation);
    }
}

