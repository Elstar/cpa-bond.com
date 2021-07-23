<?php

namespace App\DataFixtures;

use App\Entity\PreLanding;
use Doctrine\Persistence\ObjectManager;

class PreLandingFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(PreLanding::class, 10, function (PreLanding $preLanding) {
            $preLanding
                ->setName($this->faker->name)
                ->setUrl($this->faker->url)
                ->setCr($this->faker->numberBetween(10, 100))
            ;
        });

        $manager->flush();
    }
}
