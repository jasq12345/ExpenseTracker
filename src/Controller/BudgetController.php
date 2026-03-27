<?php

namespace App\Controller;

use App\Dto\Budget\CreateBudgetDto;
use App\Dto\Budget\UpdateBudgetDto;
use App\Dto\Pagination\PaginationDto;
use App\Provider\Pagination\BudgetPaginationProvider;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use App\Service\PaginationResponseBuilderService;
use App\Service\UserProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/budgets')]
class BudgetController extends AbstractController
{
    #[Route('/', name: 'app_budget_list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationDto $dto,
        UserProviderService $providerService,
        PaginationResponseBuilderService $builder,
        BudgetPaginationProvider $provider
    ): JsonResponse
    {
        $user = $providerService->getUser();

        return $this->json(
            $builder->build($dto, $provider, $user),
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }

    #[Route('/{id}', name: 'app_budget_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        BudgetRepository $repository,
        int $id
    ): JsonResponse
    {
        $budget = $repository->find($id);

        return $this->json(
            $budget,
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }

    #[Route('/', name: 'app_budget_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateBudgetDto $dto,
        BudgetService $service
    ): JsonResponse
    {
        $service->create($dto);

        return $this->json(['message' => 'Budget created successfully'], 201);
    }

    #[Route('/', name: 'app_budget_update', methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateBudgetDto $dto,
        BudgetService $service,
    ): JsonResponse
    {
        $service->update($dto);

        return $this->json(['message' => 'Budget updated successfully'], 201);
    }
}
