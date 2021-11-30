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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="postbacks")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadCreate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadApprove;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadDecline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $leadTrash;

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

    public function getLeadApprove(): ?string
    {
        return $this->leadApprove;
    }

    public function setLeadApprove(?string $leadApprove): self
    {
        $this->leadApprove = $leadApprove;

        return $this;
    }

    public function getLeadDecline(): ?string
    {
        return $this->leadDecline;
    }

    public function setLeadDecline(?string $leadDecline): self
    {
        $this->leadDecline = $leadDecline;

        return $this;
    }

    public function getLeadTrash(): ?string
    {
        return $this->leadTrash;
    }

    public function setLeadTrash(?string $leadTrash): self
    {
        $this->leadTrash = $leadTrash;

        return $this;
    }

    public function setPostbackLink(string $link_postback, Lead $lead): string
    {
        if ($link_postback) {
            $link_postback = str_replace(
                [
                    '{lead_id}',
                    '{status_lead}',
                    '{utm_source}',
                    '{utm_medium}',
                    '{utm_campaign}',
                    '{utm_content}',
                    '{utm_term}',
                    '{money}',
                    '{currency}'
                ],
                [
                    $lead->getUniqueId(),
                    $lead->getStatus(),
                    $lead->getStream()->getUniqueId(),
                    $lead->getUtmMedium(),
                    $lead->getUtmCampaign(),
                    $lead->getUtmContent(),
                    $lead->getUtmTerm(),
                    $lead->getStream()->getSum(),
                    $lead->getOffer()->getCurrency()->getName()
                ],
                $link_postback
            );
        }
        return $link_postback;
    }
}
