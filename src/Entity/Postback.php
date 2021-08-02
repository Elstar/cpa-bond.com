<?php

namespace App\Entity;

use App\Repository\PostbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PostbackRepository::class)
 */
class Postback
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postbacks")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadCreate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadConfirm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadReject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadFake;

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

    public function getLeadCreate(): ?string
    {
        return $this->leadCreate;
    }

    public function setLeadCreate(?string $leadCreate): self
    {
        $this->leadCreate = $leadCreate;

        return $this;
    }

    public function getLeadConfirm(): ?string
    {
        return $this->leadConfirm;
    }

    public function setLeadConfirm(?string $leadConfirm): self
    {
        $this->leadConfirm = $leadConfirm;

        return $this;
    }

    public function getLeadReject(): ?string
    {
        return $this->leadReject;
    }

    public function setLeadReject(?string $leadReject): self
    {
        $this->leadReject = $leadReject;

        return $this;
    }

    public function getLeadFake(): ?string
    {
        return $this->leadFake;
    }

    public function setLeadFake(?string $leadFake): self
    {
        $this->leadFake = $leadFake;

        return $this;
    }
}
