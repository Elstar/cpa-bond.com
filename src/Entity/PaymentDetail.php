<?php

namespace App\Entity;

use App\Repository\PaymentDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PaymentDetailRepository::class)
 */
class PaymentDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PayOutMethods::class, inversedBy="paymentDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payOutMethods;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Translatable
     */
    private $detailProperty;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayOutMethods(): ?PayOutMethods
    {
        return $this->payOutMethods;
    }

    public function setPayOutMethods(?PayOutMethods $payOutMethods): self
    {
        $this->payOutMethods = $payOutMethods;

        return $this;
    }

    public function getDetailProperty(): ?string
    {
        return $this->detailProperty;
    }

    public function setDetailProperty(string $detailProperty): self
    {
        $this->detailProperty = $detailProperty;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
