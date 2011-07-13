<?php

namespace Palleas\SchedulerBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Palleas\SchedulerBundle\Scheduler\Finder;

/**
* 
*/
class UpdateScheduledCommand extends ContainerAwareCommand
{
    const ARGUMENT_ADAPTER = 'adapter';
    
    protected function configure()
    {
        $this
            ->setName('scheduler:update')
            ->setDescription('Update scheduled commands')
            ->addArgument(self::ARGUMENT_ADAPTER, InputArgument::OPTIONAL, 'Adapter to use', 'crontab');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adapterName = $input->getArgument(self::ARGUMENT_ADAPTER);
        $adapterClass = new \ReflectionClass('Palleas\\SchedulerBundle\\Adapter\\'.ucfirst(strtolower($adapterName)));
        $adapter = $adapterClass->newInstance();

        $finder = new Finder($this->getContainer()->get('kernel'));
        $commands = $finder->all();

        $adapter->update($commands, $this->getContainer()->get('kernel'));
    }
}