<?php

namespace Palleas\SchedulerBundle\Scheduler;

/**
* 
*/
class Frequency
{
    private $value;

    public function __construct($value, $command)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }
}