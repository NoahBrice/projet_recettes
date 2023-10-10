<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFormType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\IngredientType;
use App\Form\IngredientType_V3;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')] // Création d'une route 
    public function index(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->findAll();
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient_greater_than', name: 'app_ingredient_greater_than')]
    public function index_only_greater_than_100(IngredientRepository $repository): Response
    {
        // Utilisations de la fonction findAllGreaterThanPrice créer dans les repository de ingrédient 
        // et qui permet de trier les ingrédient grace au prix
        $ingredients = $repository->findAllGreaterThanPrice(100);
        // dd($ingredients);    

        //Renvoie de la vue ingrédient index
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
    // création d'une route et définition de la méthode a utiliser
    #[Route('/ingredient/create', name: 'app_ingredient_create', methods: ['GET'])]
    public function create()
    {
        // Création d'un formulaire grace au form builder
        $crea_form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('save', SubmitType::class, ['label' => 'Creation Ingredient'])
            ->setAction($this->generateUrl('app_ingredient_store'))
            ->setMethod('POST')
            ->getForm();

        // Envoie de la vue ingredient/create
        return $this->render('ingredient/create.html.twig', [
            'crea_form' => $crea_form->createView(), // envoie de la variable contenant le formulaire dans la vue
        ]);
    }

    #[Route('/ingredient/store', name: 'app_ingredient_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entity_manager)
    {
        // récupération de $request sous forme de tableaux
        $data = $request->request->all();

        // Création d'un nouvel ingrédient
        $ingredient = new Ingredient();

        // Stockage des différente propriétées dans le nouvel ingrédient
        $ingredient->setNom($data['form']['nom']);
        $ingredient->setPrix($data['form']['prix']);
        $entity_manager->persist($ingredient);
        $entity_manager->flush($ingredient);

        // Redirection ver la route app_ingredient ( ingredient/index )
        return $this->redirectToRoute('app_ingredient');
    }

    // Combinaison des méthode create et store pour réduire le code
    #[Route('/ingredient/create_and_store', name: 'ingredient.create_store', methods: ['POST', 'GET'])]
    function create_and_store(Request $request, EntityManagerInterface $entity_manager): Response
    {
        // Création d'un nouvel ingrédient
        $ingredient = new Ingredient();

        // Création du formulaire 
        $crea_form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('save', SubmitType::class, ['label' => 'Creation Ingredient'])
            ->setAction($this->generateUrl('ingredient.create_store'))
            ->setMethod('POST')
            ->getForm();
        // traite le résultat de la requête afin de déterminer son état
        $crea_form->handleRequest($request);
        // Permet de savoir si la requête a été envoyé et si elle est valide 
        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            // Récupération des données de la requête
            $data = $request->request->all();
            // dd($data);
            //Création d'un nouvel ingrédient
            $ingredient = new Ingredient();
            $ingredient->setNom($data['form']['nom']);
            $ingredient->setPrix($data['form']['prix']);
            $entity_manager->persist($ingredient);
            $entity_manager->flush($ingredient);

            //Redirection vers la route app_ingredient 
            return $this->redirectToRoute('app_ingredient');
        } else {

            // Renvoie la vue avec le formulaire de création
            return $this->render('ingredient/create.html.twig', [
                'crea_form' => $crea_form->createView(),
            ]);
        }
    }

    // Deuxième manière de créer un formulaire 
    #[Route('/ingredient/create_and_store_V2', name: 'ingredient.create_store_V2', methods: ['POST', 'GET'])]
    function create_and_store_V2(Request $request, EntityManagerInterface $entity_manager): Response
    {

        $crea_form = $this->createForm(IngredientType::class);


        $crea_form->handleRequest($request);

        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            // if ($crea_form->isSubmitted()) {
            $data = $crea_form->getData();
            // dd($data);
            $ingredient = new Ingredient();
            $ingredient->setNom($data->getNom());
            $ingredient->setPrix($data->getPrix());
            $entity_manager->persist($ingredient);
            $entity_manager->flush($ingredient);
            $this->addFlash('success', 'Votre ingrédient ' . $data->getNom() . ' a bien été créé avec succès !');
            return $this->redirectToRoute('app_ingredient');
        } else {
            return $this->render('ingredient/create_2.html.twig', [
                'crea_form' => $crea_form->createView(),
            ]);
        }
    }


    #[Route('/ingredient/create_and_store_V3', name: 'ingredient.create_store_V3', methods: ['POST', 'GET'])]
    function create_and_store_V3(Request $request, EntityManagerInterface $entity_manager): Response
    {
        $ingredient = new Ingredient();

        $crea_form = $this->createForm(IngredientFormType::class, $ingredient, ['submit label' => 'Créer l\'ingrédient']);


        $crea_form->handleRequest($request);

        // if ($crea_form->isSubmitted()) {
        //     $data = $crea_form->getData();
        //     dd($data);}
        if ($crea_form->isSubmitted() && $crea_form->isValid()) {

            // if ($crea_form->isSubmitted()) {
            $data = $crea_form->getData();
            // dd($data);
            $ingredient->setNom($data->getNom());
            $ingredient->setPrix($data->getPrix());
            $entity_manager->persist($ingredient);
            $entity_manager->flush($ingredient);
            $this->addFlash('success', 'Votre ingrédient ' . $data->getNom() . ' a bien été créé avec succès !');
            return $this->redirectToRoute('app_ingredient');
        } else {
            return $this->render('ingredient/create_3.html.twig', [
                'crea_form' => $crea_form->createView(),
            ]);
        }
    }


    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['PUT', 'GET'])]
    function edit(Int $id, Request $request, EntityManagerInterface $entity_manager, IngredientRepository $repository): Response
    {
        $ingredient = $repository->find($id);

        $form = $this->createForm(IngredientType_V3::class, $ingredient, ['method' => 'PUT', 'submit label' => 'Modifier l\'ingrédient']);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if ($form->isSubmitted()) {
            $data = $form->getData();
            // dd($data);
            $entity_manager->persist($ingredient);
            $entity_manager->flush($ingredient);
            $this->addFlash('success', 'Votre ingrédient ' . $data->getNom() . ' a bien été modifié avec succès !');
            return $this->redirectToRoute('app_ingredient');
        } else {
            return $this->render('ingredient/create_3.html.twig', [
                'crea_form' => $form->createView(),
            ]);
        }
    }

    #[Route('/ingredient/delete/{id}', name: 'ingredient.destroy', methods: ['GET'])]
    function destroy(Int $id, EntityManagerInterface $entity_manager, IngredientRepository $repository): Response
    {
        $ingredient = $repository->find($id);
        $nom = $ingredient->getNom();
        $entity_manager->remove($ingredient);
        $entity_manager->flush();
        $this->addFlash('success', 'Votre ingrédient ' . $nom . ' a bien été supprimé avec succès !');
        return $this->redirectToRoute('app_ingredient');
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode Query Builder 
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/ingredient/find_test', name: 'app_ingredient_find_test', methods: ['GET'])] // Création d'une route 
    public function find_test(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient("test");
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_test_20', name: 'app_ingredient_find_test_20', methods: ['GET'])] // Création d'une route 
    public function find_test_20(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_nom_prix("test", 20);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_testLike_20', name: 'app_ingredient_find_testLike_20', methods: ['GET'])] // Création d'une route 
    public function find_testLike_20(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_nomLike_prix("test", 20);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_by/{prix}', name: 'app_ingredient_find_by_prix', methods: ['GET'])] // Création d'une route 
    public function find_by_prix(IngredientRepository $repository, int $prix): Response
    {

        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_by_prix($prix);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_by/{prix}/by_nom/{nom}', name: 'app_ingredient_find_by_prix_and_name', methods: ['GET'])] // Création d'une route 
    public function find_by_prix_and_name(IngredientRepository $repository, int $prix, string $nom): Response
    {

        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_by_prix_and_name($prix, $nom);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode SQL 
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/ingredient/index_sql', name: 'app_ingredient_index_sql', methods: ['GET'])] // Création d'une route 
    public function index_sql(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_all_sql();
        // dd($ingredients);    
        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index_sql.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }



    #[Route('/ingredient/find_nom_sql/{nom}', name: 'app_ingredient_find_nom_sql', methods: ['GET'])] // Création d'une route 
    public function find_nom_sql(IngredientRepository $repository, string $nom): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_sql($nom);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index_sql.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_test_10_sql', name: 'app_ingredient_find_test_10_sql', methods: ['GET'])] // Création d'une route 
    public function find_test_10_sql(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_nom_prix_sql("test", 10);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index_sql.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredient/find_testLike_10_sql', name: 'app_ingredient_find_testLike_10_sql', methods: ['GET'])] // Création d'une route 
    public function find_testLike_10_sql(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_ingredient_nomLike_prix_sql("test", 10);
        // dd($ingredients);    

        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index_sql.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Méthode DQL
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/ingredient/index_dql', name: 'app_ingredient_index_dql', methods: ['GET'])] // Création d'une route 
    public function index_dql(IngredientRepository $repository): Response
    {
        //récupération des ingrédient grâce au repository
        $ingredients = $repository->find_all_dql();
        // dd($ingredients);    
        // Renvoie de la vue index/ingrédient
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }
}
