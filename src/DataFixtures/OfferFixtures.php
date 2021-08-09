<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Geo;
use App\Entity\Offer;
use App\Entity\PayTypes;
use App\Repository\LandingRepository;
use App\Repository\PreLandingPageRepository;
use App\Repository\PreLandingRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends BaseFixtures implements DependentFixtureInterface
{


    private PreLandingRepository $preLandingRepository;
    private LandingRepository $landingRepository;
    private PreLandingPageRepository $preLandingPageRepository;

    public function __construct(PreLandingRepository $preLandingRepository, LandingRepository $landingRepository, PreLandingPageRepository $preLandingPageRepository)
    {
        $this->preLandingRepository = $preLandingRepository;
        $this->landingRepository = $landingRepository;
        $this->preLandingPageRepository = $preLandingPageRepository;
    }

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Offer::class, 5, function (Offer $offer) {
            $category = $this->getRandomReference(Category::class);
            $offer
                ->setName($this->faker->name)
                ->setGeoInfo($this->faker->text)
                ->setSourceTraffic($this->faker->text)
                ->setForbiddenSources($this->faker->text)
                ->setPaySum($this->faker->numberBetween(100, 500))
                ->setCategory($category)
                ->setCurrency($this->getRandomReference(Currency::class))
                ->setTranslatableLocale('uk')
            ;
            $offer->addPayType($this->getRandomReference(PayTypes::class));
            $offer->addGeo($this->getRandomReference(Geo::class));
            if ($this->faker->boolean) {
                $preLanding = $this->preLandingRepository->findOneBy(['category' => $category]);
                $offer->addPreLanding($preLanding);

                $landing = $this->landingRepository->findOneBy(['category' => $category]);
                $offer->addLanding($landing);
            } else {
                if ($this->faker->boolean) {
                    $preLandingPage = $this->preLandingPageRepository->findOneBy(['category' => $category]);
                    $offer->addPreLanding($preLandingPage);
                } else {
                    $landing = $this->landingRepository->findOneBy(['category' => $category]);
                    $offer->addLanding($landing);
                }
            }
        });



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            GeoFixtures::class,
            ModelOfPayFixtures::class,
            CurrensyFixtures::class,
            PreLandingFixtures::class,
            LandingFixtures::class,
            PreLandingPageFixtures::class
        ];
    }
}
