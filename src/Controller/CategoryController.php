<?php

namespace App\Controller;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    protected function getReadGroup(): string
    {
        return 'category:read';
    }

    #[Route('/categories', name: 'app_category_get_all', methods: ['GET'])]
    public function getAllCategories(CategoryService $service): JsonResponse
    {
        $categories = $service->getAll();
        
        return $this->json($categories, 200, [], ['groups' => $this->getReadGroup()]);
    }
}
