<?php

namespace App\Command;


use App\Service\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
final class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var \App\Service\UserManager
     */
    private $userManager;

    /**
     * CreateUserCommand constructor.
     *
     * @param  \App\Service\UserManager  $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED);
    }

    /**
     * Accepting inputs and passing them to userManager
     *
     * @inheritDoc
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['User Creator', '']);
        $response = $this->userManager->create(
          $input->getArgument('username'),
          $input->getArgument('password'),
          $input->getArgument('email')
        );
        $output->writeln($response);
    }
}