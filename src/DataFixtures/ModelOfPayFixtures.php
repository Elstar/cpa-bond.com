<?php

namespace App\DataFixtures;

use App\Entity\PayTypes;
use Doctrine\Persistence\ObjectManager;

class ModelOfPayFixtures extends BaseFixtures
{

    function loadData(ObjectManager $manager)
    {
        $types = ['CPL', 'CPA', 'CPB'];
        $this->createMany(PayTypes::class, 1, function (PayTypes $payTypes) use ($types) {
            $payTypes
                ->setName($this->faker->randomElement($types))
                ->setDescription($this->faker->text)
            ;
        });
        $manager->flush();
    }
}
