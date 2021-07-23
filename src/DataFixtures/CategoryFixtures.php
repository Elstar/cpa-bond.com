<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
        });
        $manager->flush();
    }
}
