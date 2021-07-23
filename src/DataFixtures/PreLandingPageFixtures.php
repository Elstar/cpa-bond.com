<?php

namespace App\DataFixtures;

use App\Entity\PreLandingPage;
use Doctrine\Persistence\ObjectManager;

class PreLandingPageFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(PreLandingPage::class, 10, function (PreLandingPage $preLandingPage) {
            $preLandingPage
                ->setName($this->faker->name)
                ->setUrl($this->faker->url)
                ->setCr($this->faker->numberBetween(10, 100))
            ;
        });

        $manager->flush();
    }
}
