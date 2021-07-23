<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=StreamRepository::class)
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
    private ?User $User;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Offer $Offer;

    /**
     * @ORM\ManyToOne(targetEntity=PreLanding::class)
     */
    private ?PreLanding $PreLanding;

    /**
     * @ORM\ManyToOne(targetEntity=Landing::class)
     */
    private ?Landing $Landing;

    /**
     * @ORM\ManyToOne(targetEntity=PreLandingPage::class)
     */
    private ?PreLandingPage $PreLandingPage;

    /**
     * @ORM\ManyToOne(targetEntity=Geo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Geo $Geo;

    /**
     * @ORM\ManyToOne(targetEntity=PayTypes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PayTypes $PayType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

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
        return $this->Offer;
    }

    public function setOffer(?Offer $Offer): self
    {
        $this->Offer = $Offer;

        return $this;
    }

    public function getPreLanding(): ?PreLanding
    {
        return $this->PreLanding;
    }

    public function setPreLanding(?PreLanding $PreLanding): self
    {
        $this->PreLanding = $PreLanding;

        return $this;
    }

    public function getLanding(): ?Landing
    {
        return $this->Landing;
    }

    public function setLanding(?Landing $Landing): self
    {
        $this->Landing = $Landing;

        return $this;
    }

    public function getPreLandingPage(): ?PreLandingPage
    {
        return $this->PreLandingPage;
    }

    public function setPreLandingPage(?PreLandingPage $PreLandingPage): self
    {
        $this->PreLandingPage = $PreLandingPage;

        return $this;
    }

    public function getGeo(): ?Geo
    {
        return $this->Geo;
    }

    public function setGeo(?Geo $Geo): self
    {
        $this->Geo = $Geo;

        return $this;
    }

    public function getPayType(): ?PayTypes
    {
        return $this->PayType;
    }

    public function setPayType(?PayTypes $PayType): self
    {
        $this->PayType = $PayType;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
