<?php

namespace Palleas\SchedulerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Palleas\SchedulerBundle\Scheduler\Finder;


/**
* 
*/
class ListScheduledCommand extends ContainerAwareCommand
{
    private $kernel;

    protected function configure()
    {
        $this
            ->setName('scheduler:commands');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder( $this->getContainer()->get('kernel'));

        foreach ($finder->all() as $command) {
            $output->writeln(sprintf("<info>%s :</info> (%s)\n<comment>%s</comment>",
                $command->getName(),
                $command->getFrequency()->get(),
                $command->getDescription()));
        }

    }

}