<?php

namespace Palleas\SchedulerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Palleas\SchedulerBundle\Scheduler\Every;

/**
* @Every("5min") 
*/
class PointlessCommand extends Command
{
    protected $quotes = array(
        'I say no to drugs, but they don’t listen.',
        'If aliens are looking for intelligent life?! WHY THE HECK ARE YOU SCARED?!',
        'A day without sunshine is like, you know, night.',
        'It’s so simple to be wise. Just think of something stupid to say and then don’t say it.',
        'If you have noticed this notice you will have noticed that this notice is not worth noticing',
        'Smoking kills. If you\'re killed, you\'ve lost a very important part of your life.',
    );

    protected function configure()
    {
        $this
            ->setName('scheduler:pointless')
            ->setDescription('Do something pointless.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->quotes[mt_rand(0, sizeof($this->quotes) - 1)]);
    }

}


























