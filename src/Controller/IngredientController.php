<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        // dd($ingredients);    
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient_greater_than', name: 'app_ingredient_greater_than')]
    public function index_only_greater_than_100(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAllGreaterThanPrice(100);
        // dd($ingredients);    
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
}
