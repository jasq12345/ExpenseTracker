<?php

namespace App\EventListener;

use App\Event\TransactionCreatedEvent;
use App\Service\Notification\BudgetAlertService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: TransactionCreatedEvent::class, method: 'onTransactionCreated')]
final readonly class BudgetAlertListener
{
    public function __construct(
        private BudgetAlertService $alertService
    ) {}

    public function onTransactionCreated(TransactionCreatedEvent $event): void
    {
        $this->alertService->checkAndAlert($event->getBudget());
    }
}
