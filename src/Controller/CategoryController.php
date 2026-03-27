<?php

namespace App\Controller;

use App\Dto\Category\CreateCategoryDto;
use App\Dto\Category\UpdateCategoryDto;
use App\Dto\Pagination\PaginationDto;
use App\Provider\Pagination\CategoryPaginationProvider;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\PaginationResponseBuilderService;
use App\Service\UserProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationDto $dto,
        UserProviderService $providerService,
        PaginationResponseBuilderService $builder,
        CategoryPaginationProvider $provider,
    ): JsonResponse
    {
        $user = $providerService->getUser();

        return $this->json(
            $builder->build($dto, $provider, $user),
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }

    #[Route('/{id}', name: 'app_category_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(CategoryRepository $repository, int $id): JsonResponse
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

    #[Route('/', name: 'app_category_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCategoryDto $dto,
        CategoryService $service
    ): JsonResponse {
        $service->create($dto);

        return $this->json(['message' => 'Category created successfully'], 201);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['DELETE'])]
    public function delete(CategoryRepository $repository, CategoryService $service, int $id): JsonResponse
    {
        $category = $repository->find($id);

        $service->delete($category);

        return $this->json(['message' => 'Category deleted successfully'], 201);

    }
    #[Route('/{id}', name: 'app_category_update', methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateCategoryDto $dto,
        CategoryService $service,
        CategoryRepository $repository,
        int $id
    ): JsonResponse
    {
        $category = $repository->find($id);

        $service->update($category, $dto);

        return $this->json(['message' => 'Category updated successfully'], 201);

    }
}
