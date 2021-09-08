<?php

namespace App\Entity;

use App\Repository\PayOutMethodsRepository;
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
}
