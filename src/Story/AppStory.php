<?php

namespace App\Story;

use App\Enum\TransactionType;
use App\Factory\BudgetFactory;
use App\Factory\CategoryFactory;
use App\Factory\TransactionFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        $john = UserFactory::createOne([
            'username' => 'john',
            'email' => 'john@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'roles' => ['ROLE_USER'],
        ]);

        $food = CategoryFactory::createOne([
            'name' => 'Food',
            'user' => $john,
            'color' => '#EF4444',
            'icon' => 'utensils',
        ]);

        $transport = CategoryFactory::createOne([
            'name' => 'Transport',
            'user' => $john,
            'color' => '#3B82F6',
            'icon' => 'bus',
        ]);

        $bills = CategoryFactory::createOne([
            'name' => 'Bills',
            'user' => $john,
            'color' => '#10B981',
            'icon' => 'wallet',
        ]);

        $health = CategoryFactory::createOne([
            'name' => 'Health',
            'user' => $john,
            'color' => '#8B5CF6',
            'icon' => 'heart',
        ]);

        $entertainment = CategoryFactory::createOne([
            'name' => 'Entertainment',
            'user' => $john,
            'color' => '#F59E0B',
            'icon' => 'film',
        ]);

        // Suma wydatkow ponizej: 855.94
        BudgetFactory::createOne([
            'user' => $john,
            'month' => (int) date('m'),
            'year' => (int) date('Y'),
            'limitAmount' => '3000.00',
            'spentAmount' => '855.94',
            'currentAmount' => '2144.06',
        ]);

        // 1-3 (dotychczasowe)
        TransactionFactory::createOne([
            'name' => 'Lunch',
            'category' => $food,
            'user' => $john,
            'price' => 10.50,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Lunch in city center',
            'createdAt' => new \DateTimeImmutable('-9 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Bus Ticket',
            'category' => $transport,
            'user' => $john,
            'price' => 2.75,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Daily commute',
            'createdAt' => new \DateTimeImmutable('-8 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Groceries',
            'category' => $food,
            'user' => $john,
            'price' => 125.40,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Weekly groceries',
            'createdAt' => new \DateTimeImmutable('-7 days'),
        ]);

        // +7 roznych transakcji
        TransactionFactory::createOne([
            'name' => 'Electricity Bill',
            'category' => $bills,
            'user' => $john,
            'price' => 210.00,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Monthly electricity payment',
            'createdAt' => new \DateTimeImmutable('-6 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Internet Subscription',
            'category' => $bills,
            'user' => $john,
            'price' => 79.99,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Home internet',
            'createdAt' => new \DateTimeImmutable('-5 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Gym Membership',
            'category' => $health,
            'user' => $john,
            'price' => 99.00,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Monthly gym fee',
            'createdAt' => new \DateTimeImmutable('-4 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Cinema Tickets',
            'category' => $entertainment,
            'user' => $john,
            'price' => 45.00,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Weekend movie',
            'createdAt' => new \DateTimeImmutable('-3 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Fuel',
            'category' => $transport,
            'user' => $john,
            'price' => 180.30,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Fuel refill',
            'createdAt' => new \DateTimeImmutable('-2 days'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Coffee Beans',
            'category' => $food,
            'user' => $john,
            'price' => 38.20,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Specialty coffee',
            'createdAt' => new \DateTimeImmutable('-1 day'),
        ]);

        TransactionFactory::createOne([
            'name' => 'Pharmacy',
            'category' => $health,
            'user' => $john,
            'price' => 64.80,
            'amount' => 1,
            'type' => TransactionType::EXPENSE,
            'description' => 'Medicines and supplements',
            'createdAt' => new \DateTimeImmutable('now'),
        ]);
    }
}
