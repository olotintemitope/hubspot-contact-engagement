<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AlfadocsHubspotContactsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('alfadocs-hubspot-contacts')
            ->setDescription('PHP tool that reads all activities associated to contacts in Hubspot and then prints a simple text statistic')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer();

        $contactEvent = $service->get('alfadocs_hubspot_contacts');

        $output->writeln('===== Display Statistics =====');

        $contactEvent->displayContactEngagements();

        $output->writeln('====== End Of Display ======');
    }

}
