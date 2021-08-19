<?php

namespace App\DataFixtures;

use App\Entity\Geo;
use App\Entity\Landing;
use App\Entity\Offer;
use App\Entity\PayTypes;
use App\Entity\PreLanding;
use App\Entity\Stream;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StreamFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Stream::class, 5, function (Stream $stream) {
            /**
             * @var Offer $offer
             */
            $offer = $this->getRandomReference(Offer::class);
            $stream
                ->setUniqueId(uniqid())
                ->setUser($this->getRandomReference(User::class))
                ->setName($this->faker->name)
                ->setOffer($offer)
                ->setLanding($this->getRandomReference(Landing::class))
                ->setGeo($this->getRandomReference(Geo::class))
                ->setPayType($this->getRandomReference(PayTypes::class))
                ->setUrl($this->faker->url)
                ->setSum($offer->getPaySum())
            ;
            if ($this->faker->boolean)
                $stream->setPreLanding($this->getRandomReference(PreLanding::class));
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            OfferFixtures::class,
            LandingFixtures::class,
            PreLandingFixtures::class,
            GeoFixtures::class,
            ModelOfPayFixtures::class
        ];
    }
}
