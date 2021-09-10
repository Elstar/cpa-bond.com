<?php

namespace App\Entity;

use App\Repository\BalanceOperationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=BalanceOperationsRepository::class)
 */
class BalanceOperations
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lead::class, inversedBy="balanceOperations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lead;

    /**
     * @ORM\Column(type="float")
     */
    private $sum;

    /**
     * @ORM\Column(type="integer", options={"default":0, "unsigned":true, "comment": "0 - with lead, 1 - payOuts"})
     */
    private $operationType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="balanceOperations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLead(): ?Lead
    {
        return $this->lead;
    }

    public function setLead(?Lead $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getOperationType(): ?int
    {
        return $this->operationType;
    }

    public function setOperationType(int $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
