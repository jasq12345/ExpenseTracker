<?php

namespace App\Controller;

use App\Dto\Budget\CreateBudgetDto;
use App\Dto\Transaction\CreateTransactionDto;
use App\Repository\BudgetRepository;
use App\Repository\TransactionRepository;
use App\Service\BudgetService;
use App\Service\TransactionService;
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
        $transaction = $repository->find($id);

        return $this->json(
            $transaction,
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
        $transaction = $service->create($dto);

        return $this->json(
            $transaction,
            Response::HTTP_OK,
            [],
            ['groups' => ['budget:read']]
        );
    }
}
