<?php

namespace App\Entity;

use App\Repository\StatsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=StatsRepository::class)
 * @Table(name="stats", indexes={
 *      @Index(name="dayUser", columns={"day","user_id"})
 * }, uniqueConstraints={
 *      @UniqueConstraint(name="unique_data", columns={"day", "user_id", "offer_id", "stream_id"})
 * })
 */
class Stats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Stream::class)
     */
    private $stream;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class)
     */
    private $offer;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $preLandingUniqueVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $landingUniqueVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $preLandingPageUniqueVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $preLandingVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $landingVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $preLandingPageVisits;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $newLeadCount;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $rejectedLeadCount;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $acceptedLeadCount;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $fakeLeadCount;

    /**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $leads;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $payoff;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

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

    public function getStream(): ?Stream
    {
        return $this->stream;
    }

    public function setStream(?Stream $stream): self
    {
        $this->stream = $stream;

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

    public function getPreLandingVisits(): ?int
    {
        return $this->preLandingVisits;
    }

    public function setPreLandingVisits(?int $preLandingVisits): self
    {
        $this->preLandingVisits = $preLandingVisits;

        return $this;
    }

    public function getLandingVisits(): ?int
    {
        return $this->landingVisits;
    }

    public function setLandingVisits(?int $landingVisits): self
    {
        $this->landingVisits = $landingVisits;

        return $this;
    }

    public function getPreLandingPageVisits(): ?int
    {
        return $this->preLandingPageVisits;
    }

    public function setPreLandingPageVisits(?int $preLandingPageVisits): self
    {
        $this->preLandingPageVisits = $preLandingPageVisits;

        return $this;
    }

    public function getNewLeadCount(): ?int
    {
        return $this->newLeadCount;
    }

    public function setNewLeadCount(?int $newLeadCount): self
    {
        $this->newLeadCount = $newLeadCount;

        return $this;
    }

    public function getRejectedLeadCount(): ?int
    {
        return $this->rejectedLeadCount;
    }

    public function setRejectedLeadCount(?int $rejectedLeadCount): self
    {
        $this->rejectedLeadCount = $rejectedLeadCount;

        return $this;
    }

    public function getAcceptedLeadCount(): ?int
    {
        return $this->acceptedLeadCount;
    }

    public function setAcceptedLeadCount(?int $acceptedLeadCount): self
    {
        $this->acceptedLeadCount = $acceptedLeadCount;

        return $this;
    }

    public function getFakeLeadCount(): ?int
    {
        return $this->fakeLeadCount;
    }

    public function setFakeLeadCount(?int $fakeLeadCount): self
    {
        $this->fakeLeadCount = $fakeLeadCount;

        return $this;
    }

    public function getPreLandingUniqueVisits(): ?int
    {
        return $this->preLandingUniqueVisits;
    }

    public function setPreLandingUniqueVisits(?int $preLandingUniqueVisits): self
    {
        $this->preLandingUniqueVisits = $preLandingUniqueVisits;

        return $this;
    }

    public function getLandingUniqueVisits(): ?int
    {
        return $this->landingUniqueVisits;
    }

    public function setLandingUniqueVisits(?int $landingUniqueVisits): self
    {
        $this->landingUniqueVisits = $landingUniqueVisits;

        return $this;
    }

    public function getPreLandingPageUniqueVisits(): ?int
    {
        return $this->preLandingPageUniqueVisits;
    }

    public function setPreLandingPageUniqueVisits(?int $preLandingPageUniqueVisits): self
    {
        $this->preLandingPageUniqueVisits = $preLandingPageUniqueVisits;

        return $this;
    }

    public function getLeads(): ?int
    {
        return $this->leads;
    }

    public function setLeads(int $leads): self
    {
        $this->leads = $leads;

        return $this;
    }

    public function getPayoff(): ?float
    {
        return $this->payoff;
    }

    public function setPayoff(?float $payoff): self
    {
        $this->payoff = $payoff;

        return $this;
    }
}
