<?php

namespace App\EventListener;

use App\Enum\BudgetPolicyEnum;
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
        $policy = $event->getBudget()->getBudgetPolicy()->getPolicy();

        if ($policy === BudgetPolicyEnum::UNLIMITED) {
            return;
        }

        $this->alertService->checkAndAlert($event->getBudget());
    }
}
