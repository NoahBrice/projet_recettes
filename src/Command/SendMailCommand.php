<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\SendMailService;
use App\Repository\UtilisateurRepository;

#[AsCommand(
    name: 'app:send-mail',
    description: 'Envoie un mail à tous les utilisateurs',
)]
class SendMailCommand extends Command
{
    public function __construct(private SendMailService $mailer, private UtilisateurRepository $repository)
    {
        parent::__construct();
    }
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $utilisateur = $this->repository->findAll();
        $this->mailer->dire_bonjour_a_tous(
            $utilisateur,
            'no-reply@monsite.net',
            'Titre de mon message',
            'bonjour'
        );

        return Command::SUCCESS;
    }
}
