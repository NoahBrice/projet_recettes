<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/ingredient/create', name: 'app_ingredient_create', methods: ['GET'])]
    public function create()
    {
        $crea_form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('save', SubmitType::class, ['label' => 'Creation Ingredient'])
            ->setAction($this->generateUrl('app_ingredient_store'))
            ->setMethod('POST')
            ->getForm();
        return $this->render('ingredient/create.html.twig', [
            'crea_form' => $crea_form->createView(),
        ]);
    }

    #[Route('/ingredient/store', name: 'app_ingredient_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entity_manager)
    {
        $data = $request->request->all();
        dd($data);

        $ingredient = new Ingredient();
        $ingredient->setNom();
        $ingredient->setPrix();
        $entity_manager->persist($ingredient);
        $entity_manager->flush($ingredient);
    }
}
