<?php

namespace App\Entity;

use App\Repository\PayOutMethodsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PayOutMethodsRepository::class)
 */
class PayOutMethods
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float", options={"default":1, "unsigned":true})
     */
    private $convertRate;

    /**
     * @ORM\Column(type="float", options={"default":0, "unsigned":true})
     */
    private $commission;

    /**
     * @ORM\OneToMany(targetEntity=PaymentDetail::class, mappedBy="payOutMethods")
     */
    private $detailProperty;

    /**
     * @ORM\OneToMany(targetEntity=PaymentDetail::class, mappedBy="payOutMethods", orphanRemoval=true)
     */
    private $paymentDetails;

    /**
     * @ORM\Column(type="boolean", options={"default":true, "comment": "false - not active, true - active"})
     */
    private $active;

    public function __construct()
    {
        $this->detailProperty = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getConvertRate(): ?float
    {
        return $this->convertRate;
    }

    public function setConvertRate(float $convertRate): self
    {
        $this->convertRate = $convertRate;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * @return Collection|PaymentDetail[]
     */
    public function getDetailProperty(): Collection
    {
        return $this->detailProperty;
    }

    public function addDetailProperty(PaymentDetail $detailProperty): self
    {
        if (!$this->detailProperty->contains($detailProperty)) {
            $this->detailProperty[] = $detailProperty;
            $detailProperty->setPayOutMethods($this);
        }

        return $this;
    }

    public function removeDetailProperty(PaymentDetail $detailProperty): self
    {
        if ($this->detailProperty->removeElement($detailProperty)) {
            // set the owning side to null (unless already changed)
            if ($detailProperty->getPayOutMethods() === $this) {
                $detailProperty->setPayOutMethods(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PaymentDetail[]
     */
    public function getPaymentDetails(): Collection
    {
        return $this->paymentDetails;
    }

    public function addPaymentDetail(PaymentDetail $paymentDetail): self
    {
        if (!$this->paymentDetails->contains($paymentDetail)) {
            $this->paymentDetails[] = $paymentDetail;
            $paymentDetail->setPayOutMethods($this);
        }

        return $this;
    }

    public function removePaymentDetail(PaymentDetail $paymentDetail): self
    {
        if ($this->paymentDetails->removeElement($paymentDetail)) {
            // set the owning side to null (unless already changed)
            if ($paymentDetail->getPayOutMethods() === $this) {
                $paymentDetail->setPayOutMethods(null);
            }
        }

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }
}
