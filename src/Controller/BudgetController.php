<?php

namespace App\Controller;

use App\Repository\BudgetRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
            ['groups' => ['category:read']]
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
            ['groups' => ['transaction:read']]
        );
    }
}
