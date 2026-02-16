<?php

namespace App\Entity;

use App\Entity\ValueObject\BudgetPolicy;
use App\Repository\BudgetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $limitAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $currentAmount;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $month;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $year;

    #[ORM\ManyToOne(inversedBy: 'budgets')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[Orm\Embedded(class: BudgetPolicy::class)]
    private BudgetPolicy $budgetPolicy;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $spentAmount;

    public function __construct()
    {
        $this->budgetPolicy = BudgetPolicy::strict();
        $this->month = (int) date('m');
        $this->year = (int) date('Y');
        $this->currentAmount = 0.00;
        $this->spentAmount = 0.00;
    }
    public function getSpentAmount(): string
    {
        return $this->spentAmount;
    }

    public function setSpentAmount(string $spentAmount): self
    {
        $this->spentAmount = $spentAmount;
        return $this;
    }

    public function addExpense(float $amount): self
    {
        $this->spentAmount += $amount;
        $this->currentAmount -= $amount;
        return $this;
    }

    public function addIncome(float $amount): self
    {
        $this->currentAmount += $amount;
        return $this;
    }
    public function getBudgetPolicy(): BudgetPolicy
    {
        return $this->budgetPolicy;
    }

    public function setBudgetPolicy(BudgetPolicy $config): void
    {
        $this->budgetPolicy = $config;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLimitAmount(): ?string
    {
        return $this->limitAmount;
    }

    public function setLimitAmount(string $limitAmount): static
    {
        $this->limitAmount = $limitAmount;

        return $this;
    }

    public function getCurrentAmount(): ?string
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount(string $currentAmount): static
    {
        $this->currentAmount = $currentAmount;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
