<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

readonly class RateLimitListener
{
    public function __construct(
        private RateLimiterFactory $apiReadLimiter,
        private RateLimiterFactory $apiReportsLimiter,
        private RateLimiterFactory $apiAuthLimiter,
        private RateLimiterFactory $apiWriteLimiter,
        private RateLimiterFactory $apiRefreshLimiter,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $method = $request->getMethod();

        $limiter = match (true) {
            str_starts_with($path, '/api/auth/refresh') => $this->apiRefreshLimiter,
            str_starts_with($path, '/api/auth/login') => null,
            str_starts_with($path, '/api/reports') => $this->apiReportsLimiter,
            str_starts_with($path, '/api/auth') => $this->apiAuthLimiter,
            in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']) => $this->apiWriteLimiter,
            str_starts_with($path, '/api') => $this->apiReadLimiter,
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
