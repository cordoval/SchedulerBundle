<?php

namespace Palleas\SchedulerBundle\Scheduler;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder as FileFinder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Doctrine\Common\Annotations\AnnotationReader;

use Palleas\SchedulerBundle\Scheduler\Command as ScheduledCommand;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
* 
*/
class Finder
{
    private $kernel;

    private $reader;

    public function __construct(Kernel $kernel, AnnotationReader $reader = null)
    {
        $this->kernel = $kernel;
        
        $this->reader = $reader ?: new AnnotationReader();
        $this->reader->setAutoloadAnnotations(true);

        $this->reader->setAnnotationCreationFunction(function($name, array $values) {
            return new $name($values['value']);
        });
    }

    public function all() 
    {
        $commands = array();

        foreach ($this->kernel->getBundles() as $bundle) {
            $commands = array_merge($commands, $this->findCommands($bundle));
        }
        
        return $commands;        
    }

    public function getCurrentCommand() 
    {
        return $this->currentCommand;
    }

    protected function findCommands(Bundle $bundle)
    {
        if (!is_dir($path = $bundle->getPath().DIRECTORY_SEPARATOR.'Command')) {
            return array();
        }

        $commands = array();

        foreach ($this->getFinder($path) as $classfile) {
            if ($instance = $this->instanciate($bundle, $classfile)) {
                $commands[] = $instance;
            }
        }

        return $commands;
    }

    protected function getFinder($path)
    {
        $finder = new FileFinder();

        return $finder->files()->name('/\.php$/')->in($path);
    }

    protected function instanciate(Bundle $bundle, \SPLFileInfo $file)
    {
        // Building full classname (w/ namespace)
        $classname = $bundle->getNamespace()
            .implode('\\', explode('/', str_replace($bundle->getPath(), '', $file->getPath())))
            .'\\'.$file->getBasename('.php');

        $reflector = new \ReflectionClass($classname);

        if (!$reflector->isInstantiable()) {
            return;
        }

        $frequency = $this->reader->getClassAnnotation($reflector, 'Palleas\\SchedulerBundle\\Scheduler\\Frequency');

        return $frequency 
            ? new ScheduledCommand($reflector->newInstance(), $frequency)
            : null;
    }
}