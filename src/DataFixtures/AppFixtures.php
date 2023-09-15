<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker_factory = Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom( $faker_factory->word());
            $ingredient->setPrix($faker_factory->randomFloat(2,10,200));
            $manager->persist($ingredient);
            $manager->flush($ingredient);
        }
    }
}
