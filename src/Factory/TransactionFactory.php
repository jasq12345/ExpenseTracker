<?php

namespace App\Factory;

use App\Entity\Transaction;
use App\Enum\TransactionType;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Transaction>
 */
final class TransactionFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Transaction::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return function (): array {
            $user = UserFactory::new();

            return [
                'name' => self::faker()->words(3, true),
                'amount' => self::faker()->numberBetween(1, 5),
                'price' => self::faker()->randomFloat(2, 1, 500),
                'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-6 months', 'now')),
                'type' => self::faker()->randomElement(TransactionType::cases()),
                'user' => $user,
                'category' => CategoryFactory::new(['user' => $user]),
                'description' => self::faker()->optional()->sentence(),
            ];
        };
    }
}
