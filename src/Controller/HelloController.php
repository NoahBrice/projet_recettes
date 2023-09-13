<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
        ]);
    }
    #[Route('/hello/show_hello', name: 'app_show_hello')]
    public function show_hello_world(): Response
    {
        return $this->render("hello/hello_world.html.twig", ['hello' => 'Hello World']);
    }
}
