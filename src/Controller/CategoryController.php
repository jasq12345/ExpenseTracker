<?php

namespace App\Controller;

use App\Dto\Category\CreateCategoryDto;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(CategoryRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOne(CategoryRepository $repository, int $id): JsonResponse
    {
        $category = $repository->find($id);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        return $this->json(
            $category,
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCategoryDto $dto,
        CategoryService $service
    ): JsonResponse {
        $category = $service->create($dto);

        return $this->json(
            $category,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['category:read']]
        );
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(CategoryRepository $repository, CategoryService $service, int $id): JsonResponse
    {
        $category = $repository->find($id);

        $service->delete($category);

        return $this->json(
            $category,
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }
}
