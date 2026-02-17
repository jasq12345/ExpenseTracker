<?php

namespace App\Controller;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CategoryController extends AbstractController
{
    protected function getReadGroup(): string
    {
        return 'category:read';
    }

    #[Route('/categories', name: 'app_category_get_all', methods: ['GET'])]
    public function getAllCategories(CategoryService $service): JsonResponse
    {

    }
}
