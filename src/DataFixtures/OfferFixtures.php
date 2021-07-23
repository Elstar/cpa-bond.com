<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Geo;
use App\Entity\Offer;
use App\Entity\PayTypes;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Offer::class, 5, function (Offer $offer) {
            $offer
                ->setName($this->faker->name)
                ->setGeoInfo($this->faker->text)
                ->setSourceTraffic($this->faker->text)
                ->setForbiddenSources($this->faker->text)
                ->setPaySum($this->faker->numberBetween(100, 500))
                ->setCategory($this->getRandomReference(Category::class))
                ->setCurrency($this->getRandomReference(Currency::class))
            ;
            $offer->addPayType($this->getRandomReference(PayTypes::class));
            $offer->addGeo($this->getRandomReference(Geo::class));
        });



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            GeoFixtures::class,
            ModelOfPayFixtures::class,
            CurrensyFixtures::class
        ];
    }
}
