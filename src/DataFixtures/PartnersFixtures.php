<?php

namespace App\DataFixtures;

use App\Entity\Partners;
use Doctrine\Persistence\ObjectManager;

class PartnersFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Partners::class, 1, function (Partners $partners) {
            $partners
                ->setName('Nserv')
                ->setDataFormat('xml')
                ->setHttpServerSend("https://nservn.com/gateway/export_orders.php")
                ->setApiKey($this->faker->linuxPlatformToken)
            ;
        });

        $manager->flush();
    }
}
