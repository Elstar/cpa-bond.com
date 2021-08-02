<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Table(name="category", uniqueConstraints={@UniqueConstraint(name="unique_name", columns={"name", "parent_id"})})
 * @Gedmo\TranslationEntity(class="App\Entity\CategoryTranslation")
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
     * @ORM\Column(type="integer", options={"default": 0, "unsigned":true})
     */
    private $parentId;

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
     * @ORM\OneToMany(
     *   targetEntity="CategoryTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function addTranslation(CategoryTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

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

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function setTranslations($at)
    {
        foreach ($at as $t) {
            $this->addTranslation($t);
        }
        return $this;
    }
}
