<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        $this->loadData($manager);

        $this->manager->flush();
    }

    abstract function loadData(ObjectManager $manager);

    protected function create(string $className, callable $factory)
    {
        $entity = new $className();
        $factory($entity);

        $this->manager->persist($entity);

        return $entity;
    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i=0; $i<$count; $i++) {
            $entity = $this->create($className, $factory);
            $this->addReference($className . "|$i", $entity);
        }
    }

    private $referencesIndex = [];

    protected function getRandomReference($className)
    {
        if (! isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $reference) {
                if (strpos($key, $className . '|') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new \Exception('Не найдены ссылки на класс: ' . $className);
        }

        return $this->getReference($this->faker->randomElement($this->referencesIndex[$className]));
    }
}
