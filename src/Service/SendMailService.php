<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Utilisateur;

class SendMailService
{
    public $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        // On envoie le mail
        $this->mailer->send($email);
    }

    function dire_bonjour_a_tous(
        $utilisateurs,
        string $from,
        string $subject,
        string $template,
    ): void {

        foreach ($utilisateurs as $utilisateur) {
            $email = (new TemplatedEmail())
                ->from($from)
                ->to($utilisateur->getEmail())
                ->subject($subject)
                ->htmlTemplate("emails/$template.html.twig")
                ->context(["prenom" => $utilisateur->getPrenom(), "nom" => $utilisateur->getNom()]);

            // On envoie le mail
            $this->mailer->send($email);
        }
    }


    public function dire_bonjour(
        $utilisateur,
        string $from,
        string $subject,
        string $template
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($utilisateur->getEmail())
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context(["prenom" => $utilisateur->getPrenom(), "nom" => $utilisateur->getNom(), "mail" => $utilisateur->getEmail()]);

        // On envoie le mail
        $this->mailer->send($email);
    }
}
