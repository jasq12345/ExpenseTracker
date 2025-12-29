<?php

namespace App\Entity;

use App\Enum\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['transaction:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['transaction:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'category_id', nullable: true, onDelete: "SET NULL")]
    #[Groups(['transaction:read'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    #[Groups(['transaction:read'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['transaction:read'])]
    private ?string $price = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['transaction:read'])]
    private ?int $amount = null;

    #[ORM\Column]
    #[Groups(['transaction:read'])]
    private ?\DateTime $date = null;

    #[ORM\Column]
    #[Groups(['transaction:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['transaction:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, enumType: TransactionType::class)]
    #[Groups(['transaction:read'])]
    private ?TransactionType $type = TransactionType::EXPENSE;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime|string $date): self
    {
        try{
            if (is_string($date)) {
                $date = new \DateTime($date);
            }
        } catch (Exception){
            throw new InvalidArgumentException("Invalid date format");
        }


        $this->date = $date;
        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    public function setType(TransactionType $type): self
    {
        $this->type = $type;
        return $this;
    }
}
