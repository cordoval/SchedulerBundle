<?php

namespace Palleas\SchedulerBundle\Tests\Scheduler;

use Palleas\SchedulerBundle\Scheduler\Every as BaseEvery;
/**
* 
*/
class Every extends \PHPUnit_Framework_TestCase
{

    public function testParsing()
    {
        $every = new BaseEvery('5min');

        $this->assertEquals(5, $every->getCount());
        $this->assertEquals('min', $every->getUnit());
    }
}