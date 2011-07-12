<?php

namespace Palleas\SchedulerBundle\EventListener;

use Symfony\Component\HttpKernel\Event\Event;

/**
* 
*/
class ControllerListener
{

    public function onKernelRequest(Event $event)
    {
        var_dump($event); die('--');
    }

}