<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecetteRepository;
use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RecetteFormType;


class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette')]
    public function index(RecetteRepository $repository): Response
    {
        $recettes = $repository->findAll();
        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/create_and_store', name: 'recette.create_store', methods: ['POST', 'GET'])]
    function create_and_store(Request $request, EntityManagerInterface $entity_manager): Response
    {
        $recette = new Recette();

        $crea_form = $this->createForm(RecetteFormType::class, $recette, ['submit label' => 'Créer la recette']);


        $crea_form->handleRequest($request);


        if ($crea_form->isSubmitted() && $crea_form->isValid()) {

            $data = $crea_form->getData();
            // dd($data);
            $recette->setNom($data->getNom());
            $recette->setPrix($data->getPrix());
            $entity_manager->persist($recette);
            $entity_manager->flush($recette);
            $this->addFlash('success', 'Votre ingrédient ' . $data->getNom() . ' a bien été créé avec succès !');
            return $this->redirectToRoute('app_recette');
        } else {
            return $this->render('recette/create.html.twig', [
                'crea_form' => $crea_form->createView(),
            ]);
        }
    }
}
