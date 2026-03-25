<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

readonly class RateLimitListener
{
    public function __construct(
        private RateLimiterFactory $apiGeneralLimiter,
        private RateLimiterFactory $apiReportsLimiter,
        private RateLimiterFactory $apiAuthLimiter,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        $limiter = match (true) {
            str_starts_with($path, '/api/reports') => $this->apiReportsLimiter,
            str_starts_with($path, '/api/auth') => $this->apiAuthLimiter,
            str_starts_with($path, '/api') => $this->apiGeneralLimiter,
            default => null,
        };

        if (!$limiter) return;

        if (!$limiter->create($request->getClientIp())->consume()->isAccepted()) {
            $event->setResponse(
                new JsonResponse(['message' => 'Too many requests'],429)
            );
        }
    }
}
