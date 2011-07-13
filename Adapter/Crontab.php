<?php

namespace Palleas\SchedulerBundle\Adapter;

use Palleas\SchedulerBundle\Scheduler\Command;
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

        $this->clearCrontab();
        $this->flush();
    }

    protected function writeCommand(Command $command, $runner, $logFile)
    {
        $line = '';

        $frequency = $command->getFrequency();
        if ($frequency instanceof Every) {
            $times = array();
            foreach (Every::getUnits() as $unit) {
                $times[] = $unit === $frequency->getUnit() 
                    ? sprintf('*/%d', $frequency->getCount())
                    : '*';
            }

            $line = implode(' ', $times);
        } else if ($frequency instanceof On) {
            $line = $frequency->get();            
        } else {
            throw new \RuntimeException(sprintf('Unsupported annotation "%s" !', get_class($frequency)));
        }

        $this->content .= sprintf('%s %s %s >> %s',
            $line,
            $runner,
            $command->getName(),
            $logFile) . "\n";
    }

    protected function flush()
    {
        $crontab = new Process('crontab -', null, null, $this->content);
        $crontab->run();        
    }

    protected function clearCrontab()
    {
        $command = new Process('crontab -r');
    }

}