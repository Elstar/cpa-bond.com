<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
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
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $geoInfo;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $sourceTraffic;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $forbiddenSources;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=PayTypes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $payType;

    /**
     * @ORM\ManyToMany(targetEntity=Geo::class)
     */
    private $geo;

    /**
     * @ORM\Column(type="float", options={"default": 0, "unsigned":true})
     */
    private $paySum;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\ManyToMany(targetEntity=PreLanding::class)
     */
    private $preLanding;

    /**
     * @ORM\ManyToMany(targetEntity=Landing::class)
     */
    private $landing;

    /**
     * @ORM\ManyToMany(targetEntity=PreLandingPage::class)
     */
    private $preLandingPage;

    /**
     * @ORM\ManyToOne(targetEntity=Partners::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partner;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true, "comment": "Sum in lead for clients"})
     */
    private $sum;

    public function __construct()
    {
        $this->Geo = new ArrayCollection();
        $this->preLanding = new ArrayCollection();
        $this->landing = new ArrayCollection();
        $this->preLandingPage = new ArrayCollection();
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
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

    public function getGeoInfo(): ?string
    {
        return $this->geoInfo;
    }

    public function setGeoInfo(?string $geoInfo): self
    {
        $this->geoInfo = $geoInfo;

        return $this;
    }

    public function getSourceTraffic(): ?string
    {
        return $this->sourceTraffic;
    }

    public function setSourceTraffic(?string $sourceTraffic): self
    {
        $this->sourceTraffic = $sourceTraffic;

        return $this;
    }

    public function getForbiddenSources(): ?string
    {
        return $this->forbiddenSources;
    }

    public function setForbiddenSources(?string $forbiddenSources): self
    {
        $this->forbiddenSources = $forbiddenSources;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPayType(): ?PayTypes
    {
        return $this->payType;
    }

    public function setPayType(PayTypes $payType): self
    {
        $this->payType = $payType;

        return $this;
    }

    /**
     * @return Collection|Geo[]
     */
    public function getGeo(): ?Collection
    {
        return $this->geo;
    }

    public function addGeo(Geo $geo): self
    {
        if (!$this->Geo->contains($geo)) {
            $this->Geo[] = $geo;
        }

        return $this;
    }

    public function removeGeo(Geo $geo): self
    {
        $this->Geo->removeElement($geo);

        return $this;
    }

    public function getPaySum(): ?float
    {
        return $this->paySum;
    }

    public function setPaySum(float $paySum): self
    {
        $this->paySum = $paySum;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    /**
     * @return Collection|PreLanding[]
     */
    public function getPreLanding(): Collection
    {
        return $this->preLanding;
    }

    public function addPreLanding(PreLanding $preLanding): self
    {
        if (!$this->preLanding->contains($preLanding)) {
            $this->preLanding[] = $preLanding;
        }

        return $this;
    }

    public function removePreLanding(PreLanding $preLanding): self
    {
        $this->preLanding->removeElement($preLanding);

        return $this;
    }

    /**
     * @return Collection|Landing[]
     */
    public function getLanding(): Collection
    {
        return $this->landing;
    }

    public function addLanding(Landing $landing): self
    {
        if (!$this->landing->contains($landing)) {
            $this->landing[] = $landing;
        }

        return $this;
    }

    public function removeLanding(Landing $landing): self
    {
        $this->landing->removeElement($landing);

        return $this;
    }

    /**
     * @return Collection|PreLandingPage[]
     */
    public function getPreLandingPage(): Collection
    {
        return $this->preLandingPage;
    }

    public function addPreLandingPage(PreLandingPage $preLandingPage): self
    {
        if (!$this->preLandingPage->contains($preLandingPage)) {
            $this->preLandingPage[] = $preLandingPage;
        }

        return $this;
    }

    public function removePreLandingPage(PreLandingPage $preLandingPage): self
    {
        $this->preLandingPage->removeElement($preLandingPage);

        return $this;
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

    public function getSum(): ?int
    {
        return $this->sum;
    }

    public function setSum(int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }
}
