<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Table(name="category", uniqueConstraints={@UniqueConstraint(name="unique_name", columns={"name", "parent_id"})})
 * @Gedmo\Tree(type="nested")
 */
class Category
{

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
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", options={"default":0})
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", options={"default":0})
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", options={"default":0})
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Category|null $parent
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity=PreLanding::class, mappedBy="category")
     */
    private $preLandings;

    /**
     * @ORM\OneToMany(targetEntity=Landing::class, mappedBy="category")
     */
    private $landings;

    /**
     * @ORM\OneToMany(targetEntity=PreLandingPage::class, mappedBy="category")
     */
    private $preLandingPages;

    public function __construct()
    {
        $this->preLandings = new ArrayCollection();
        $this->landings = new ArrayCollection();
        $this->preLandingPages = new ArrayCollection();
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

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getLvl()
    {
        return $this->lvl;
    }

    public function setDefautl()
    {
        $this->lvl = 0;
        $this->lft = 0;
        $this->rgt = 0;

        return $this;
    }

    /**
     * @return Collection|PreLanding[]
     */
    public function getPreLandings(): Collection
    {
        return $this->preLandings;
    }

    public function addPreLanding(PreLanding $preLanding): self
    {
        if (!$this->preLandings->contains($preLanding)) {
            $this->preLandings[] = $preLanding;
            $preLanding->setCategory($this);
        }

        return $this;
    }

    public function removePreLanding(PreLanding $preLanding): self
    {
        if ($this->preLandings->removeElement($preLanding)) {
            // set the owning side to null (unless already changed)
            if ($preLanding->getCateg() === $this) {
                $preLanding->setCateg(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Landing[]
     */
    public function getLandings(): Collection
    {
        return $this->landings;
    }

    public function addLanding(Landing $landing): self
    {
        if (!$this->landings->contains($landing)) {
            $this->landings[] = $landing;
            $landing->setCategory($this);
        }

        return $this;
    }

    public function removeLanding(Landing $landing): self
    {
        if ($this->landings->removeElement($landing)) {
            // set the owning side to null (unless already changed)
            if ($landing->getCategory() === $this) {
                $landing->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PreLandingPage[]
     */
    public function getPreLandingPages(): Collection
    {
        return $this->preLandingPages;
    }

    public function addPreLandingPage(PreLandingPage $preLandingPage): self
    {
        if (!$this->preLandingPages->contains($preLandingPage)) {
            $this->preLandingPages[] = $preLandingPage;
            $preLandingPage->setCategory($this);
        }

        return $this;
    }

    public function removePreLandingPage(PreLandingPage $preLandingPage): self
    {
        if ($this->preLandingPages->removeElement($preLandingPage)) {
            // set the owning side to null (unless already changed)
            if ($preLandingPage->getCategory() === $this) {
                $preLandingPage->setCategory(null);
            }
        }

        return $this;
    }

}
