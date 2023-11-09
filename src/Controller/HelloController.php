<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Taxe\CalculatorTaxe;

class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(CalculatorTaxe $calculatorTaxe): Response
    {
        $prix = [];
        $prixHT = 360;
        $prix['TTC'] = $calculatorTaxe->calculerTTC($prixHT);
        $prix['TVA'] =$calculatorTaxe->calculerTVA($prixHT);
        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
            'prix' => $prix,
        ]);
    }
    #[Route('/hello/show_hello', name: 'app_show_hello')]
    public function show_hello_world(): Response
    {
        return $this->render("hello/hello_world.html.twig", ['hello' => 'Hello World']);
    }
}
