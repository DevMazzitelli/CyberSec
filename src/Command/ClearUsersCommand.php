<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearUsersCommand extends Command
{
    protected static $defaultName = 'app:clear-users';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:clear-user') // Assurez-vous que cette ligne est présente
            ->setDescription('Delete all users from the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        // Assurez-vous de remplacer 'user' par le nom de votre table utilisateur
        $connection->executeStatement($platform->getTruncateTableSQL('user', true));

        $output->writeln('All users have been deleted.');

        return Command::SUCCESS;
    }
}
