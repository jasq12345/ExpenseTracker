<?php

namespace App\Controller;

use App\Dto\Budget\CreateBudgetDto;
use App\Dto\Budget\UpdateBudgetDto;
use App\Repository\BudgetRepository;
use App\Service\BudgetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/budgets')]
class BudgetController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(BudgetRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOne(BudgetRepository $repository, int $id): JsonResponse
    {
        $budget = $repository->find($id);

        return $this->json(
            $budget,
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }

    #[Route('/', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateBudgetDto $dto,
        BudgetService $service
    ): JsonResponse
    {
        $budget = $service->create($dto);

        return $this->json(
            $budget,
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }

    #[Route('/', methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateBudgetDto $dto,
        BudgetService $service,
    ): JsonResponse
    {
        $budget = $service->update($dto);

        return $this->json(
            $budget,
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }
}
