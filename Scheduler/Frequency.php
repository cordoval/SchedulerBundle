<?php

namespace Palleas\SchedulerBundle\Scheduler;

/**
* 
*/
abstract class Frequency
{
    private $value;

    public function __construct($value)
    {
        $this->setValue($value);
    }

    public function get()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}