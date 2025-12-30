<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\EntityFacade;
use App\Service\Factory\ApiErrorResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends GenericApiController
{
    protected function getReadGroup(): string
    {
        return 'transaction:read';
    }
    public function __construct(TransactionRepository $categoryRepository, EntityFacade $facade, ApiErrorResponseFactory $errorResponseFactory)
    {
        parent::__construct(Transaction::class, $categoryRepository, $facade, $errorResponseFactory);
    }
    #[Route('/api/transactions/', name: 'app_transactions', methods: ['GET'])]
    public function getAllTransactions(): JsonResponse
    {
        return parent::getAllEntities();
    }

    #[Route('/api/transaction/{id}', name: 'app_transaction_get', methods: ['GET'])]
    public function getOneTransaction(int $id): JsonResponse
    {
        return parent::getOneEntity($id);
    }

    #[Route('/api/transaction/{id}', name: 'app_transaction_delete', methods: ['DELETE'])]
    public function deleteTransaction(int $id): JsonResponse
    {
        return parent::deleteEntity($id);
    }

    #[Route('/api/transaction/', name: 'app_transaction_post', methods: ['POST'])]
    public function newTransaction(Request $request): JsonResponse
    {
        return parent::newEntity($request);
    }

    #[Route('/api/transaction/{id}', name: 'app_transaction_put', methods: ['PUT', 'PATCH'])]
    public function updateTransaction(Request $request, int $id): JsonResponse
    {
        return parent::updateEntity($request, $id);
    }
}
