<?php

namespace App\Entity;

use App\Repository\LeadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Darsyn\IP\Version\Multi as IP;

/**
 * @ORM\Entity(repositoryClass=LeadRepository::class)
 * @Table(name="lead",indexes={
 *     @Index(name="hash", columns={"hash"}),
 *     @Index(name="streamIp", columns={"stream_id", "ip"}),
 *     @Index(name="userDate", columns={"user_id", "created_at"})
 * })
 */
class Lead
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Stream::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $stream;

    /**
     * @ORM\ManyToOne(targetEntity=Geo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $geo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="ip")
     */
    private $ip;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ua;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $hash;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true, "comment": "0 - new, 1 - rejected, 2 - accepted, 3 - fake"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rejectType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusComment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $utmMedium;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $utmCampaign;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $utmContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $utmTerm;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 0, "unsigned":true, "comment": "0 - unpayed, 1 - payed"})
     */
    private $payStatus;

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private ?string $uniqueId;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true, "comment": "0 - not sended, 1 - sended"})
     */
    private $gatewayStatus;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"comment": "lead price for client"})
     */
    private $sum;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment": "serialize request data"})
     */
    private $fullRequestData;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $referer;

    /**
     * @ORM\OneToMany(targetEntity=BalanceOperations::class, mappedBy="lead")
     */
    private $balanceOperations;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true, options={"comment": "response data from partner"})
     */
    private $responseFromPartner;

    public function __construct()
    {
        $this->balanceOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStream(): ?Stream
    {
        return $this->stream;
    }

    public function setStream(?Stream $Stream): self
    {
        $this->stream = $Stream;

        return $this;
    }

    public function getGeo(): ?Geo
    {
        return $this->geo;
    }

    public function setGeo(?Geo $Geo): self
    {
        $this->geo = $Geo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->firstName = $FirstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function setIp(IP $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUa(): ?string
    {
        return $this->ua;
    }

    public function setUa(string $ua): self
    {
        $this->ua = $ua;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

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

    public function getRejectType(): ?int
    {
        return $this->rejectType;
    }

    public function setRejectType(?int $rejectType): self
    {
        $this->rejectType = $rejectType;

        return $this;
    }

    public function getStatusComment(): ?string
    {
        return $this->statusComment;
    }

    public function setStatusComment(?string $statusComment): self
    {
        $this->statusComment = $statusComment;

        return $this;
    }

    public function getUtmMedium(): ?string
    {
        return $this->utmMedium;
    }

    public function setUtmMedium(?string $utmMedium): self
    {
        $this->utmMedium = $utmMedium;

        return $this;
    }

    public function getUtmCampaign(): ?string
    {
        return $this->utmCampaign;
    }

    public function setUtmCampaign(?string $utmCampaign): self
    {
        $this->utmCampaign = $utmCampaign;

        return $this;
    }

    public function getUtmContent(): ?string
    {
        return $this->utmContent;
    }

    public function setUtmContent(?string $utmContent): self
    {
        $this->utmContent = $utmContent;

        return $this;
    }

    public function getUtmTerm(): ?string
    {
        return $this->utmTerm;
    }

    public function setUtmTerm(?string $utmTerm): self
    {
        $this->utmTerm = $utmTerm;

        return $this;
    }

    public function getPayStatus(): ?int
    {
        return $this->payStatus;
    }

    public function setPayStatus(?int $payStatus): self
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(): self
    {
        $this->uniqueId = uniqid();

        return $this;
    }

    public function getGatewayStatus(): ?int
    {
        return $this->gatewayStatus;
    }

    public function setGatewayStatus(int $gatewayStatus): self
    {
        $this->gatewayStatus = $gatewayStatus;

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

    public function getFullRequestData(): ?string
    {
        return $this->fullRequestData;
    }

    public function setFullRequestData(string $fullRequestData): self
    {
        $this->fullRequestData = $fullRequestData;

        return $this;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * @return Collection|BalanceOperations[]
     */
    public function getBalanceOperations(): Collection
    {
        return $this->balanceOperations;
    }

    public function addBalanceOperation(BalanceOperations $balanceOperation): self
    {
        if (!$this->balanceOperations->contains($balanceOperation)) {
            $this->balanceOperations[] = $balanceOperation;
            $balanceOperation->setLead($this);
        }

        return $this;
    }

    public function removeBalanceOperation(BalanceOperations $balanceOperation): self
    {
        if ($this->balanceOperations->removeElement($balanceOperation)) {
            // set the owning side to null (unless already changed)
            if ($balanceOperation->getLead() === $this) {
                $balanceOperation->setLead(null);
            }
        }

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

    public function getResponseFromPartner(): ?string
    {
        return $this->responseFromPartner;
    }

    public function setResponseFromPartner(?string $responseFromPartner): self
    {
        $this->responseFromPartner = $responseFromPartner;

        return $this;
    }
}
