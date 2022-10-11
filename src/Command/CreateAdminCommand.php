<?php

namespace App\Command;

use App\Command\Question\EmailQuestion;
use App\Command\Question\NameQuestion;
use App\Command\Question\PasswordQuestion;
use App\Dto\Request\User\RegisterUserDto;
use App\Service\RegistrationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Command for creating user with ADMIN role',
)]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly RegistrationService $registrationService,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->askQuestion(new EmailQuestion('Please, type email:'));
        $firstname = $io->askQuestion(new NameQuestion('Please, type first name:'));
        $lastname = $io->askQuestion(new NameQuestion('Please, type last name:'));
        $password = $io->askQuestion(new PasswordQuestion('Please, type password:'));

        $dto = new RegisterUserDto($firstname, $lastname, $email,  $password);
        $this->registrationService->registerNewAdmin($dto);

        $io->success('New admin created!');

        return Command::SUCCESS;
    }
}
