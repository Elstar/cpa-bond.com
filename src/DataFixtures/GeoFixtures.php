<?php

namespace App\DataFixtures;

use App\Entity\Geo;
use Doctrine\Persistence\ObjectManager;

class GeoFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Geo::class, 1, function (Geo $geo) {
            $geo
                ->setName('UA')
            ;
        });

        $manager->flush();
    }
}
