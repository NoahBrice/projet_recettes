<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Repository\IngredientRepository;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker_factory = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom("ingr_" . $faker_factory->word());
            $ingredient->setPrix($faker_factory->randomFloat(2, 10, 200));
            $manager->persist($ingredient);
            $manager->flush($ingredient);
        }

        for ($i = 0; $i < 50; $i++) {
            $recette = new Recette();
            $recette->setNom($faker_factory->word());
            $recette->setPrix($faker_factory->randomFloat(2, 10, 500));
            $recette->setTemps($faker_factory->numberBetween(1, 240));
            $recette->setDifficulte($faker_factory->numberBetween(0, 5));
            $recette->setDescription($faker_factory->text());

            $nombreIngrédients = $faker_factory->numberBetween(2, 10);
            for ($j = 0; $j < $nombreIngrédients; $j++) {
                $ingrédient = $faker_factory->randomElement($manager->getRepository(Ingredient::class)->findAll()); // Référence aux ingrédients créés
                $recette->addIngredient($ingrédient);
            }
            $manager->persist($recette);
            $manager->flush($recette);
        }
    }
}
