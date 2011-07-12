<?php

namespace Palleas\SchedulerBundle\Scheduler;

use Symfony\Component\Console\Command\Command as BaseCommand;

/**
* 
*/
class Command
{
    private $command;
    private $frequency;

    public function __construct(BaseCommand $command, Frequency $frequency)
    {
        $this->command = $command;
        $this->frequency = $frequency;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getName()
    {
        return $this->getCommand()
            ->getName();
    }

    public function getDescription()
    {
        return $this->getCommand()
            ->getDescription();
    }
}