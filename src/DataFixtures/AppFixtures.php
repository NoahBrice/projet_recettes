<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker_factory = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $ingredient = new Ingredients();
            $ingredient->setNom( $faker_factory->word());
            $ingredient->setPrix(mt_rand(10, 100));
            $manager->persist($ingredient);
            $manager->flush($ingredient);
        }
    }
}
