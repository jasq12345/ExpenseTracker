<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends GenericApiController
{
    public function __construct(TransactionRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct(Transaction::class, $categoryRepository, $entityManager);
    }
    #[Route('/api/transaction', name: 'app_transactions', methods: ['GET'])]
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

    #[Route('/api/transaction', name: 'app_transaction_post', methods: ['POST'])]
    public function newTransaction(Request $request, PostRequestValidator $validation): JsonResponse
    {
        return parent::newEntity($request, $validation);
    }
}
