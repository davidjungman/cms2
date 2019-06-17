<?php

namespace App\Command;


use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class InitCommand extends Command
{
    protected static $defaultName = 'app:init';

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * CreateInitCommand constructor.
     *
     * @param  \Doctrine\ORM\EntityManagerInterface  $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
          ->addArgument('companyName', InputArgument::REQUIRED)
          ->addArgument('version', InputArgument::REQUIRED);
    }

    /**
     * Accepting inputs and creating Settings Entity
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
        $output->writeln(['Init Application', '']);
        $settings = new Settings();
        $settings
          ->setCompanyName($input->getArgument("companyName"))
          ->setVersion($input->getArgument("version"));

        $this->em->persist($settings);
        $this->em->flush();

        $output->writeln("Init was successfully completed");
    }
}