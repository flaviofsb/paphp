<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:cria_admin',
    description: 'cria o admin',
)]
class CriaAdminCommand extends Command
{
    public function __construct(

        private UserPasswordHasherInterface $hasher, 
        private UserRepository $users
    )
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = "flavio@afixo.com.br";
        $password = "123456";
        $nome = "Administrador do Banco";
        
        
        $admin = new User();
        $admin->setEmail($email);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, $password)
            );
        $admin->setNome($nome);
        $admin->setDataHoraCriacao(new \DateTime());
        $this->users->save($admin, true);

        $io->success('Admin adicionado com sucesso');

        return Command::SUCCESS;
    }
}
