<?php

namespace App\Entity;

use App\Repository\DayStatsRepository;
use Doctrine\ORM\Mapping as ORM;
use Darsyn\IP\Version\Multi as IP;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=DayStatsRepository::class)
 */
class DayStats
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\Column(type="ip")
     */
    private $ip;

    /**
     * @ORM\Column(type="text")
     */
    private $ua;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $uaHash;

    /**
     * @ORM\Column(type="integer", options={"default":1, "unsigned":true})
     */
    private $visits;

    /**
     * @ORM\ManyToOne(targetEntity=Stream::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $stream;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", options={"default":0, "unsigned":true})
     */
    private $preLandingVisits;

    /**
     * @ORM\Column(type="integer", options={"default":0, "unsigned":true})
     */
    private $landingVisits;

    /**
     * @ORM\Column(type="integer", options={"default":0, "unsigned":true})
     */
    private $preLandingPageVisits;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUaHash(): ?string
    {
        return $this->uaHash;
    }

    public function setUaHash(string $uaHash): self
    {
        $this->uaHash = $uaHash;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(int $visits): self
    {
        $this->visits = $visits;

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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

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
}
