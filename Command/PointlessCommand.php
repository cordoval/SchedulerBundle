<?php

namespace Palleas\SchedulerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Palleas\SchedulerBundle\Scheduler\Frequency as Every;

/**
* @Every("5min") 
*/
class PointlessCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('scheduler:pointless')
            ->setDescription('Do something pointless.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("Hi, it's already <info>%s</info> !", date('H:i:s')));
    }    

}