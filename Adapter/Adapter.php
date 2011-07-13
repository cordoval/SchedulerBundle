<?php

namespace Palleas\SchedulerBundle\Adapter;

use Symfony\Component\HttpKernel\Kernel;

interface Adapter
{

    function update(array $commands, Kernel $kernel);

}