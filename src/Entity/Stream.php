<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=StreamRepository::class)
 * @Table(name="stream", uniqueConstraints={@UniqueConstraint(name="unique_id", columns={"unique_id"})})
 */
class Stream
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="streams")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Offer $offer;

    /**
     * @ORM\ManyToOne(targetEntity=PreLanding::class)
     */
    private ?PreLanding $preLanding;

    /**
     * @ORM\ManyToOne(targetEntity=Landing::class)
     */
    private ?Landing $landing;

    /**
     * @ORM\ManyToOne(targetEntity=PreLandingPage::class)
     */
    private ?PreLandingPage $preLandingPage;

    /**
     * @ORM\ManyToOne(targetEntity=Geo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Geo $geo;

    /**
     * @ORM\ManyToOne(targetEntity=PayTypes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PayTypes $payType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url;

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private ?string $uniqueId;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $sourceTraffic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postbackCreate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postbackApprove;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postbackDecline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postbackTrash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $googleTagId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $googleTagConversionId;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $sum;

    /**
     * @ORM\Column(type="float", nullable=true, options={"comment": "Percent for pay by model CPS (pay_types)"})
     */
    private $payPercent;


    public function __construct()
    {
        $this->uniqueId = uniqid();
    }


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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPreLanding(): ?PreLanding
    {
        return $this->preLanding;
    }

    public function setPreLanding(?PreLanding $preLanding): self
    {
        $this->preLanding = $preLanding;

        return $this;
    }

    public function getLanding(): ?Landing
    {
        return $this->landing;
    }

    public function setLanding(?Landing $landing): self
    {
        $this->landing = $landing;

        return $this;
    }

    public function getPreLandingPage(): ?PreLandingPage
    {
        return $this->preLandingPage;
    }

    public function setPreLandingPage(?PreLandingPage $preLandingPage): self
    {
        $this->preLandingPage = $preLandingPage;

        return $this;
    }

    public function getGeo(): ?Geo
    {
        return $this->geo;
    }

    public function setGeo(?Geo $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getPayType(): ?PayTypes
    {
        return $this->payType;
    }

    public function setPayType(?PayTypes $payType): self
    {
        $this->payType = $payType;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(): self
    {
        $url = null;

        if (isset($this->preLanding) && $url = $this->getPreLanding()->getUrl())
            $url = $url . '?stream=' . $this->getUniqueId();
        elseif (isset($this->preLandingPage) && $url = $this->getPreLandingPage()->getUrl())
            $url = $url . '?stream=' . $this->getUniqueId();
        elseif (isset($this->landing) && $url = $this->getLanding()->getUrl())
            $url = $url . '?stream=' . $this->getUniqueId();

        $this->url = $url;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function getSourceTraffic(): ?int
    {
        return $this->sourceTraffic;
    }

    public function setSourceTraffic(int $sourceTraffic): self
    {
        $this->sourceTraffic = $sourceTraffic;

        return $this;
    }

    public function getPostbackCreate(): ?string
    {
        return $this->postbackCreate;
    }

    public function setPostbackCreate(?string $postbackCreate): self
    {
        $this->postbackCreate = $postbackCreate;

        return $this;
    }

    public function getPostbackApprove(): ?string
    {
        return $this->postbackApprove;
    }

    public function setPostbackApprove(?string $postbackApprove): self
    {
        $this->postbackApprove = $postbackApprove;

        return $this;
    }

    public function getPostbackDecline(): ?string
    {
        return $this->postbackDecline;
    }

    public function setPostbackDecline(?string $postbackDecline): self
    {
        $this->postbackDecline = $postbackDecline;

        return $this;
    }

    public function getPostbackTrash(): ?string
    {
        return $this->postbackTrash;
    }

    public function setPostbackTrash(?string $postbackTrash): self
    {
        $this->postbackTrash = $postbackTrash;

        return $this;
    }

    public function getGoogleTagId(): ?string
    {
        return $this->googleTagId;
    }

    public function setGoogleTagId(?string $googleTagId): self
    {
        $this->googleTagId = $googleTagId;

        return $this;
    }

    public function getGoogleTagConversionId(): ?string
    {
        return $this->googleTagConversionId;
    }

    public function setGoogleTagConversionId(?string $googleTagConversionId): self
    {
        $this->googleTagConversionId = $googleTagConversionId;

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

    public function getPayPercent(): ?float
    {
        return $this->payPercent;
    }

    public function setPayPercent(?float $payPercent): self
    {
        $this->payPercent = $payPercent;

        return $this;
    }
}
