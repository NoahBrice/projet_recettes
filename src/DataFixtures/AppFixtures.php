<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Utilisateur;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Repository\IngredientRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger )
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker_factory = Factory::create('fr_FR');

        //Création du compte admin
        $admin = new Utilisateur;
        $admin->setEmail("admin@a.com");
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $admin->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $admin->setNom($faker_factory->lastName());
        $admin->setPrenom($faker_factory->firstName());
        $admin->setVille($faker_factory->city());
        $admin->setCp($faker_factory->postcode());
        $manager->persist($admin);
        $manager->flush();

        //Création de 100 users
        for ($i = 0; $i < 100; $i++) {
            $admin = new Utilisateur;
            $admin->setEmail($faker_factory->email());
            $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
            $admin->setRoles(["ROLE_USER"]);
            $admin->setNom($faker_factory->lastName());
            $admin->setPrenom($faker_factory->firstName());
            $admin->setVille($faker_factory->city());
            $admin->setCp($faker_factory->postcode());
            $manager->persist($admin);
            $manager->flush();
        }


        //Création de 100 Ingrédients
        for ($i = 0; $i < 100; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setNom("ingr_" . $faker_factory->word());
            $ingredient->setSlug($this->slugger->slug($ingredient->getNom()));
            $ingredient->setPrix($faker_factory->randomFloat(2, 10, 200));
            $manager->persist($ingredient);
            $manager->flush($ingredient);
        }

        // Création de 50 recettes
        for ($i = 0; $i < 50; $i++) {
            $recette = new Recette();
            $recette->setNom($faker_factory->word());
            $recette->setPrix($faker_factory->randomFloat(2, 10, 500));
            $recette->setTemps($faker_factory->numberBetween(1, 240));
            $recette->setDifficulte($faker_factory->numberBetween(0, 5));
            $recette->setDescription($faker_factory->text());

            // Ajoute entre 2 et 10 ingrédient dans la recette
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
