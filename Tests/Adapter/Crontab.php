<?php

namespace Palleas\SchedulerBundle\Tests\Adapter;

use Palleas\SchedulerBundle\Adapter\Crontab as CrontabAdapter;

/**
* 
*/
class Crontab extends \PHPUnit_Framework_TestCase
{

    public function testAdapterParse()
    {
        $frequency = $this
            ->getMockBuilder('Palleas\\SchedulerBundle\\Scheduler\\Every')
            ->disableOriginalConstructor()
            ->getMock();
        $frequency->expects($this->any())
                    ->method('getCount')
                    ->will($this->returnValue(5));
        $frequency->expects($this->any())
                    ->method('getUnit')
                    ->will($this->returnValue('min'));

        $crontab = new CrontabAdapter();
        $this->assertEquals('*/5 * * * *', $crontab->parse($frequency));
    }    
}