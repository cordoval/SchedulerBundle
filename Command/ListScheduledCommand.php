<?php

namespace Palleas\SchedulerBundle\Command;

use Palleas\SchedulerBundle\Scheduler\Command as ScheduledCommand;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\Finder\Finder;

use Doctrine\Common\Annotations\AnnotationReader;

/**
* 
*/
class ListScheduledCommand extends ContainerAwareCommand
{
    private $kernel;

    protected function configure()
    {
        $this
            ->setName('scheduler:commands');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->kernel = $this->getContainer()->get('kernel');
        

        // TODO : use a service
        $this->reader = new AnnotationReader(); 
        $this->reader->setAutoloadAnnotations(true);
        $this->reader->setAnnotationCreationFunction(function($name, array $values) {
            return new $name($values['value'], $name);    
        });

        foreach ($this->getCommands() as $command) {
            $output->writeln(sprintf("<info>%s :</info> (%s)\n<comment>%s</comment>",
                $command->getName(),
                $command->getFrequency()->get(),
                $command->getDescription()));
        }

    }

    protected function getCommands()
    {
        $results = array();

        foreach ($this->kernel->getBundles() as $bundle) {
            $path = $bundle->getPath().DIRECTORY_SEPARATOR.'Command';
            if (!is_dir($path)) {
                continue;
            }

            $finder = new Finder();
            $finder
                ->files()
                ->name('/\.php$/')
                ->in($path);

            foreach ($finder as $command) {
                if ($instance = $this->getInstance($bundle, $command))
                {
                    $results[] = $instance;
                }
            }
        }
        return $results;
    }

    private function getInstance(Bundle $bundle, \SPLFileInfo $file)
    {
        $classname = $bundle->getNamespace()
            .implode('\\', explode('/', str_replace($bundle->getPath(), '', $file->getPath())))
            .'\\'.$file->getBasename('.php');

        $reflector = new \ReflectionClass($classname);

        if (!$reflector->isInstantiable()) {
            return;
        }

        if (0 === sizeof($annotations = $this->reader->getClassAnnotations($reflector)))
        {
            return;
        }

        if (!($instance = $reflector->newInstance()) instanceof Command) {
            return;
        }

        return new ScheduledCommand($instance, $annotations[0]);
    }
}