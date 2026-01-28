<?php

namespace App\EventListener;

use App\Exception\Auth\AssociationInvalidValueException;
use App\Exception\Auth\AssociationNullException;
use App\Exception\Auth\RefreshTokenExpiredException;
use App\Exception\Auth\RefreshTokenNotFoundException;
use App\Exception\Validation\InvalidJsonException;
use App\Exception\Validation\MissingRequiredFieldException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = match (true) {
            $exception instanceof RefreshTokenExpiredException => 401,
            $exception instanceof RefreshTokenNotFoundException => 404,
            $exception instanceof InvalidJsonException, $exception instanceof MissingRequiredFieldException,
                $exception instanceof AssociationInvalidValueException, $exception instanceof AssociationNullException => 400,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => 500,
        };

        $response = new JsonResponse([
            'error' => $exception->getMessage(),
        ], $statusCode);

        $event->setResponse($response);
    }
}
