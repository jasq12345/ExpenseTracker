<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimit;
use Symfony\Component\RateLimiter\RateLimiterFactory;

readonly class RateLimitListener
{
    private const string ATTR_RATE_LIMIT= '_rate_limit';
    public function __construct(
        private RateLimiterFactory $apiReadLimiter,
        private RateLimiterFactory $apiReportsLimiter,
        private RateLimiterFactory $apiAuthLimiter,
        private RateLimiterFactory $apiWriteLimiter,
        private RateLimiterFactory $apiRefreshLimiter,
        private Security $security,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $method = $request->getMethod();

        [$limiter, $scope]= match (true) {
            str_starts_with($path, '/api/auth/refresh') => [$this->apiRefreshLimiter, 'refresh'],
            str_starts_with($path, '/api/auth/login') => [null, null],
            str_starts_with($path, '/api/reports') => [$this->apiReportsLimiter, 'reports'],
            str_starts_with($path, '/api/auth') => [$this->apiAuthLimiter, 'auth'],
            in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true) => [$this->apiWriteLimiter, 'write'],
            str_starts_with($path, '/api') => [$this->apiReadLimiter, 'read'],
            default => [null, null],
        };

        if (!$limiter || !$scope) return;

        $key = $this->resolveKey($event, $scope);
        $rateLimit = $limiter->create($key)->consume();

        $request->attributes->set(self::ATTR_RATE_LIMIT, $rateLimit);

        if (!$rateLimit->isAccepted()) {
            $response = new JsonResponse(['message' => 'Too many requests'], 429);
            $this->applyRateLimitHeaders($response, $rateLimit);
            $event->setResponse($response);
        }
    }

    private function resolveKey(RequestEvent $event, string $scope): string
    {
        $ip = $event->getRequest() ?? 'unknown';
        $user = $this->security->getUser();

        $userIdentifier = null;
        if($user && method_exists($user, 'getUserIdentifier')) {
            $userIdentifier = $user->getUserIdentifier();
        }

        return match ($scope) {
            'write', 'reports' => $userIdentifier ? "u:$userIdentifier|ip:$ip" : "ip:$ip",
            'read' => $userIdentifier ? "u:$userIdentifier" : "ip:$ip",
            default => "ip:$ip",
        };
    }

    private function applyRateLimitHeaders(Response $response, RateLimit $rateLimit): void
    {
        $limit = (string) $rateLimit->getLimit();
        $remaining = (string) $rateLimit->getRemainingTokens();

        $response->headers->set('RateLimit-Limit', $limit);
        $response->headers->set('RateLimit-Remaining', $remaining);

        $retryAfter = $rateLimit->getRetryAfter();
        $retryAfterSeconds = max(0, $retryAfter->getTimestamp() - time());

        $response->headers->set('Retry-After', (string) $retryAfterSeconds);
        $response->headers->set('RateLimit-Reset', (string) $retryAfter->getTimestamp());
        $response->headers->set('X-RateLimit-Reset', (string) $retryAfter->getTimestamp());

    }
}
