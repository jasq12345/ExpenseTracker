<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\PostRequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    #[Route('/api/transaction', name: 'app_transactions', methods: ['GET'])]
    public function getAllTransactions(TransactionRepository $transactionRepository): JsonResponse
    {
        $transactions = $transactionRepository->findAll();

        return $this->json($transactions);
    }

    #[Route('/api/transaction/{id}', name: 'app_transaction_get', methods: ['GET'])]
    public function getOneTransaction(string $id, TransactionRepository $transactionRepository): JsonResponse
    {
        $transaction = $transactionRepository->find($id);
        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], 404);
        }
        return $this->json($transaction);
    }

    #[Route('/api/transaction/{id}', name: 'app_transaction_delete', methods: ['DELETE'])]
    public function deleteTransaction(string $id, TransactionRepository $transactionRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $transaction = $transactionRepository->find($id);
        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], 404);
        }

        try {
            $entityManager->remove($transaction);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/api/transaction', name: 'app_transaction_post', methods: ['POST'])]
    public function newTransaction(Request $request, EntityManagerInterface $entityManager, PostRequestValidator $validation): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = $validation->validateData($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $transaction = new Transaction($data);

        try {
            $entityManager->persist($transaction);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to save transaction'], 500);
        }

        return $this->json(['success' => true]);
    }
}
