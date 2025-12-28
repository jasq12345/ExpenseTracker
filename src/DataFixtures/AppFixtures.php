<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create a user
        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

        $manager->persist($user);

        // Create some categories for this user
        $foodCategory = new Category();
        $foodCategory->setName('Food');
        $foodCategory->setUser($user); // important!
        $manager->persist($foodCategory);

        $transportCategory = new Category();
        $transportCategory->setName('Transport');
        $transportCategory->setUser($user); // important!
        $manager->persist($transportCategory);

        // Create some transactions
        $t1 = new Transaction();
        $t1->setName('Lunch');
        $t1->setCategory($foodCategory);
        $t1->setUser($user); // important!
        $t1->setPrice('10.50');
        $t1->setAmount(1);
        $t1->setDate(new \DateTime());
        $t1->setCreatedAt(new \DateTimeImmutable());
        $t1->setType(TransactionType::EXPENSE);
        $manager->persist($t1);

        $t2 = new Transaction();
        $t2->setName('Bus Ticket');
        $t2->setCategory($transportCategory);
        $t2->setUser($user); // important!
        $t2->setPrice('2.75');
        $t2->setAmount(1);
        $t2->setDate(new \DateTime());
        $t2->setCreatedAt(new \DateTimeImmutable());
        $t2->setType(TransactionType::EXPENSE);
        $manager->persist($t2);

        $manager->flush();
    }
}
