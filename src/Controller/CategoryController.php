<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
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
    public function getAllCategories(CategoryRepository $repository): JsonResponse
    {
        $categories = $repository->findAll();

        return $this->json($categories, context: ['groups' => $this->getReadGroup()]);
    }

}
