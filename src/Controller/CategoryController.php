<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/api/category', name: 'app_categories', methods: ["GET"])]
    public function getAllCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json($categories);
    }

    #[Route('/api/category/{id}', name: 'app_category_get', methods: ["GET"])]
    public function getOneCategory(string $id, CategoryRepository $categoryRepository): JsonResponse
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->json(["error" => "Category not found"], 404);
        }
        return $this->json($category);
    }

    #[Route('/api/category/{id}', name: 'app_category_delete', methods: ["DELETE"])]
    public function deleteCategory(string $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            return $this->json(["error" => "Category not found"], 404);
        }

        try {
            $entityManager->remove($category);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
        return $this->json(["success" => true]);
    }

    #[Route("/api/category/", name: "app_category_post", methods: ["POST"])]
    public function newCategory(Request $request, PostRequestValidator $validation, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = $validation->validateData($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $category = new Category($data);

        try {
            $entityManager->persist($category);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to save category'], 500);
        }

        return $this->json(["success" => true]);
    }
}

