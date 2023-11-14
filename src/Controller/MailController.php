<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SendMailService;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function index(SendMailService $mailer): Response
    {
        $mailer->send(
            'no-reply@monsite.net',
            'destinataire@monsite.net',
            'Titre de mon message',
            'test',
            ['prenom' => "prenom", 'nom' => "nom"]
        );
        return $this->render('emails/sended.html.twig', [
            'mailer' => $mailer,
        ]);
    }

    #[Route('/mail/bonjour_a_tous', name: 'app_mail_bonjour_a_tous')]
    public function bonjour_a_tous(SendMailService $mailer, UtilisateurRepository $repository,): Response
    {
        $utilisateur = $repository->findAll();

        $mailer->dire_bonjour_a_tous(
            $utilisateur,
            'no-reply@monsite.net',
            'Titre de mon message',
            'bonjour'
        );
        return $this->render('emails/sended.html.twig', [
            'mailer' => $mailer,
        ]);
    }

    #[Route('/mail/dire_bonjour/{utilisateurId}', name: 'app_mail_dire_bonjour')]
    public function dire_bonjour(SendMailService $mailer, UtilisateurRepository $repository, $utilisateurId): Response
    {
        $utilisateur = $repository->find($utilisateurId);

        $mailer->dire_bonjour(
            $utilisateur,
            'no-reply@monsite.net',
            'Titre de mon message',
            'connexion_find'
        );
        return $this->render('emails/sended.html.twig', [
            'mailer' => $mailer,
        ]);
    }

    #[Route('/mail/contact', name: 'app_mail_contact')]
    public function contact(Request $request, SendMailService $mailer): Response
    {
        $crea_form = $this->createForm(ContactFormType::class);
        $crea_form->handleRequest($request);
        if ($crea_form->isSubmitted() && $crea_form->isValid()) {
            $data = $request->request->all();
            // dd($data);
            $mailer->send(
                $data["contact_form"]["email"],
                'admin@a.com',
                $data["contact_form"]["sujet"],
                'mail-contact',
                ["corp"=> $data["contact_form"]["corp_du_mail"]]);
                $this->addFlash('success','Mail envoyé');
               return $this->redirectToRoute("app_ingredient");
            
        } else {

            return $this->render('emails/contact.html.twig', ["crea_form" => $crea_form->createView()]);
        }
    }
}
