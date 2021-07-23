<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Persistence\ObjectManager;

class CurrensyFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Currency::class, 1, function (Currency $currency) {
            $currency
                ->setName('Гривна')
                ->setSign('₴')
            ;
        });

        $manager->flush();
    }
}
