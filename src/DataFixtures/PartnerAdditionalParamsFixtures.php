<?php

namespace App\DataFixtures;

use App\Entity\PartnerAdditionalParams;
use App\Entity\Partners;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PartnerAdditionalParamsFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->create(PartnerAdditionalParams::class, function (PartnerAdditionalParams $partnerAdditionalParams) {
            $partnerAdditionalParams
                ->setPartner($this->getRandomReference(Partners::class))
                ->setValueName('partner_id')
                ->setValue(15)
            ;
        });

        $this->create(PartnerAdditionalParams::class, function (PartnerAdditionalParams $partnerAdditionalParams) {
            $partnerAdditionalParams
                ->setPartner($this->getRandomReference(Partners::class))
                ->setValueName('sourse_id')
                ->setValue(64)
            ;
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PartnersFixtures::class
        ];
    }
}
