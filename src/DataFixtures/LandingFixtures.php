<?php

namespace App\DataFixtures;

use App\Entity\Landing;
use Doctrine\Persistence\ObjectManager;

class LandingFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Landing::class, 10, function (Landing $landing) {
            $landing
                ->setName($this->faker->name)
                ->setUrl($this->faker->url)
                ->setCr($this->faker->numberBetween(10, 100))
            ;
        });

        $manager->flush();
    }
}
