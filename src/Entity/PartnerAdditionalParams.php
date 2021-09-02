<?php

namespace App\Entity;

use App\Repository\PartnerAdditionalParamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartnerAdditionalParamsRepository::class)
 */
class PartnerAdditionalParams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Partners::class, inversedBy="partnerAdditionalParams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valueName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartner(): ?Partners
    {
        return $this->partner;
    }

    public function setPartner(?Partners $partner): self
    {
        $this->partner = $partner;

        return $this;
    }

    public function getValueName(): ?string
    {
        return $this->valueName;
    }

    public function setValueName(string $valueName): self
    {
        $this->valueName = $valueName;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
