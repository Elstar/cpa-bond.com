<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryFixtures extends BaseFixtures
{

    /**
     * @var array|bool|float|int|string|null
     */
    private $locales;

    public function __construct(ContainerInterface $container)
    {
        $this->locales = $container->getParameter('locales');
    }

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class, 10, function (Category $category) {
            
            $category
                ->setName($this->faker->name)
                ->setDefautl()
                ->setTranslatableLocale('uk')
            ;
        });
        $manager->flush();
    }
}
