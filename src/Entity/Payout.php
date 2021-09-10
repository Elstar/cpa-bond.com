<?php

namespace App\Entity;

use App\Model\RegistrationSpamFilter;
use App\Repository\PayoutRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=PayoutRepository::class)
 */
class Payout
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="payouts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentSystem::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentSystem;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $sum;

    /**
     * @ORM\Column(type="integer", options={"default":0, "unsigned":true, "comment": "0 - new, 1 - payed, 2 - reject"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment": "reason of reject"})
     */
    private $reason;

    /**
     * @ORM\Column(type="float")
     */
    private $finalSum;


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

    public function getPaymentSystem(): ?PaymentSystem
    {
        return $this->paymentSystem;
    }

    public function setPaymentSystem(?PaymentSystem $paymentSystem): self
    {
        $this->paymentSystem = $paymentSystem;

        return $this;
    }

    public function getSum(): ?int
    {
        return $this->sum;
    }

    public function setSum(int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $playload
     */
    public function validate(ExecutionContextInterface $context, $playload)
    {
        if ($this->getUser()->getBalance() < $context->getRoot()->get('sum')->getData()) {
            $message = 'Your balance is less than you want to payout';
            $context->buildViolation($message)
                ->atPath('sum')
                ->addViolation()
            ;
        }
    }

    public function getFinalSum(): ?float
    {
        return $this->finalSum;
    }

    public function setFinalSum(): self
    {
        $sum = $this->getSum() * $this->getPaymentSystem()->getPayoutMethod()->getConvertRate();
        $commission = $this->getPaymentSystem()->getPayoutMethod()->getCommission();
        if ($commission) {
            $sum = $sum * (1 - $commission);
        }

        $this->finalSum = round($sum, 2);

        return $this;
    }
}
