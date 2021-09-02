<?php

namespace App\Entity;

use App\Repository\PartnersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PartnersRepository::class)
 */
class Partners
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $httpServerSend;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $apiKey;

    /**
     * @ORM\Column(type="string", length=5, options={"default": "xml"})
     */
    private ?string $dataFormat;

    /**
     * @ORM\OneToMany(targetEntity=PartnerAdditionalParams::class, mappedBy="partner")
     */
    private $partnerAdditionalParams;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="partner")
     */
    private $offers;

    public function __construct()
    {
        $this->partnerAdditionalParams = new ArrayCollection();
        $this->offers = new ArrayCollection();
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

    public function getHttpServerSend(): ?string
    {
        return $this->httpServerSend;
    }

    public function setHttpServerSend(string $httpServerSend): self
    {
        $this->httpServerSend = $httpServerSend;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getDataFormat(): ?string
    {
        return $this->dataFormat;
    }

    public function setDataFormat(string $dataFormat): self
    {
        $this->dataFormat = $dataFormat;

        return $this;
    }

    /**
     * @return Collection|PartnerAdditionalParams[]
     */
    public function getPartnerAdditionalParams(): Collection
    {
        return $this->partnerAdditionalParams;
    }

    public function addPartnerAdditionalParam(PartnerAdditionalParams $partnerAdditionalParam): self
    {
        if (!$this->partnerAdditionalParams->contains($partnerAdditionalParam)) {
            $this->partnerAdditionalParams[] = $partnerAdditionalParam;
            $partnerAdditionalParam->setPartner($this);
        }

        return $this;
    }

    public function removePartnerAdditionalParam(PartnerAdditionalParams $partnerAdditionalParam): self
    {
        if ($this->partnerAdditionalParams->removeElement($partnerAdditionalParam)) {
            // set the owning side to null (unless already changed)
            if ($partnerAdditionalParam->getPartner() === $this) {
                $partnerAdditionalParam->setPartner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setPartner($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getPartner() === $this) {
                $offer->setPartner(null);
            }
        }

        return $this;
    }
}
