<?php

namespace Palleas\SchedulerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Finder\Finder;

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

        $reader = $this->getContainer()->get('annotation_reader');

        foreach ($this->getCommands() as $command) {
            $output->writeln(sprintf('<info>%s :</info> <comment>%s</comment>', $command->getName(), $command->getDescription()));
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

            foreach ($finder as $commands) {
                $classname = $bundle->getNamespace()
                    .implode('\\', explode('/', str_replace($bundle->getPath(), '', $commands->getPath())))
                    .'\\'
                    .$commands->getBasename('.php');

                $reflector = new \ReflectionClass($classname);
                if ($reflector->isInstantiable()) {
                    $instance = $reflector->newInstance();
                    var_dump($instance);
                    if ($instance instanceof \Symfony\Component\Console\Command\Command)
                    {
                       $results[] = $instance;
                    }
                }
            }
        }
        return $results;
    }
}