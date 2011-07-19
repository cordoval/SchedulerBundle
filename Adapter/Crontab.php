<?php

namespace Palleas\SchedulerBundle\Adapter;

use Palleas\SchedulerBundle\Scheduler\Command;
use Palleas\SchedulerBundle\Scheduler\Frequency;
use Palleas\SchedulerBundle\Scheduler\Every;
use Palleas\SchedulerBundle\Scheduler\On;

use Symfony\Component\HttpKernel\Kernel;

use Symfony\Component\Process\Process;

/**
* 
*/
class Crontab implements Adapter
{
    private $content;

    public function update(array $commands, Kernel $kernel)
    {
        $this->content = '';

        $runner = $kernel->getRootDir().DIRECTORY_SEPARATOR.'console';
        
        if (!file_exists($runner)) {
            throw new \InvalidArgumentException(sprintf('Invalid runner : "%s"', $runner));
        }

        foreach ($commands as $command) {
            $this->writeCommand($command, $runner, $kernel->getLogDir().DIRECTORY_SEPARATOR.'scheduler.log');
        }

        $this->clear();
        $this->flush();
    }

    public function parse(Frequency $frequency)
    {
        if ($frequency instanceof Every) {
            $times = array();

            foreach (Every::getUnits() as $unit) {

                $times[] = $unit === $frequency->getUnit() 
                    ? sprintf('*/%d', $frequency->getCount())
                    : '*';
            }

            return implode(' ', $times);
        } else if ($frequency instanceof On) {

            throw new \LogicException('@On(...) frequencies are not implemented yet.');

        }
    }

    protected function writeCommand(Command $command, $runner, $logFile)
    {
        $this->content .= sprintf('%s %s %s >> %s',
            $this->parse($command->getFrequency()),
            $runner,
            $command->getName(),
            $logFile) . "\n";
    }

    protected function flush()
    {
        $crontab = new Process('crontab -', null, null, $this->content);
        $crontab->run();        
    }

    protected function clear()
    {
        $clear = new Process('crontab -r');
        $clear->run();
    }

}