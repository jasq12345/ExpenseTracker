<?php

namespace App\Entity\ValueObject;

use App\Enum\BudgetPolicyEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class BudgetPolicy
{

    #[ORM\Column(type: Types::STRING, enumType: BudgetPolicyEnum::class)]
    private BudgetPolicyEnum $policy;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $warningThreshold = null;

    public function __construct(BudgetPolicyEnum $policy, ?int $warningThreshold = null)
    {
        if($policy === BudgetPolicyEnum::UNLIMITED)
        {
            if($warningThreshold !== null){
                throw new \InvalidArgumentException('Invalid value.');
            }
        }
        else
        {
            if($warningThreshold == null){
                throw new \InvalidArgumentException('Invalid value.');
            }
            if($warningThreshold < 0 || $warningThreshold > 100){
                throw new \InvalidArgumentException('Invalid value.');
            }
        }

        $this->policy = $policy;
        $this->warningThreshold = $warningThreshold;
    }

    public function getPolicy(): BudgetPolicyEnum
    {
        return $this->policy;
    }

    public function getWarningThreshold(): ?int
    {
        return $this->warningThreshold;
    }

    public static function strict(int $warningThreshold = 80): self
    {
        return new self(BudgetPolicyEnum::STRICT, $warningThreshold);
    }

    public static function flexible(int $warningThreshold = 80): self
    {
        return new self(BudgetPolicyEnum::FLEXIBLE, $warningThreshold);
    }

    public static function unlimited(): self
    {
        return new self(BudgetPolicyEnum::UNLIMITED);
    }
}
