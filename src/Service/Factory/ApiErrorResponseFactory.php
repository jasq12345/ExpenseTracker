<?php

namespace App\Service\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorResponseFactory
{
    public function notFound(string $entity): JsonResponse
    {
        return new JsonResponse(
            ['error' => "$entity not found"],
            Response::HTTP_NOT_FOUND
        );
    }
    public function notSaved(string $entity): JsonResponse
    {
        return new JsonResponse(
            ['error' => 'Failed to save ' . $entity],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function invalidJson(): JsonResponse
    {
        return new JsonResponse(
            ['error' => 'Invalid JSON'],
            Response::HTTP_BAD_REQUEST
        );
    }

    public function validation(array $errors): JsonResponse
    {
        return new JsonResponse(
            ['errors' => $errors],
            Response::HTTP_BAD_REQUEST
        );
    }
    public function success(?string $message = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(
            ['success' => true, 'message' => $message],
            $status
        );
    }

    public function forbidden(): JsonResponse
    {
        return new JsonResponse(
            ['error' => 'Forbidden'],
            Response::HTTP_FORBIDDEN
        );
    }
}
