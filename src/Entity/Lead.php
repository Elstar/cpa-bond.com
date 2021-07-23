<?php

namespace App\Entity;

use App\Repository\LeadRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Darsyn\IP\Version\Multi as IP;

/**
 * @ORM\Entity(repositoryClass=LeadRepository::class)
 * @Table(name="lead",indexes={@Index(name="hash", fields={"hash"})})
 */
class Lead
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="text")
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
}
