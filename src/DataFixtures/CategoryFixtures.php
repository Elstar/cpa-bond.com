<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Category::class, 10, function (Category $category) {
            $category
                ->setName($this->faker->name)
                ->setParentId(0)
            ;
            $category->addTranslation(new CategoryTranslation('ru', 'name', $this->faker->name));
            $category->addTranslation(new CategoryTranslation('uk', 'name', $this->faker->name));
            $category->addTranslation(new CategoryTranslation('en', 'name', $this->faker->name));
        });
        $manager->flush();
    }
}
