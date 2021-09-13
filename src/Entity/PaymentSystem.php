<?php

namespace App\Entity;

use App\Repository\PaymentSystemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentSystemRepository::class)
 */
class PaymentSystem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paymentSystems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=PayOutMethods::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $payoutMethod;

    /**
     * Serialize example ['detail_1' => 'value', ...]
     * @ORM\Column(type="text", nullable=true, options={"comment": "serrialize data of payment_detail fiels data"})
     */
    private $details;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPayoutMethod(): ?PayOutMethods
    {
        return $this->payoutMethod;
    }

    public function setPayoutMethod(?PayOutMethods $payoutMethod): self
    {
        $this->payoutMethod = $payoutMethod;

        return $this;
    }

    public function getDetails(): ?array
    {
        if (is_string($this->details))
            $this->details = unserialize($this->details);
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }
}
