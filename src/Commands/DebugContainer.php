<?php
/**
 * Date: 08/11/2019
 * @see       https://github.com/lpj145/expressive-tooling for the canonical source repository
 * @license   https://raw.githubusercontent.com/lpj145/expressive-tooling/master/LICENSE MIT License
 */

namespace mdantas\Expressive\Tooling\Commands;


use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugContainer extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    protected function configure()
    {
        $this
            ->setDescription('Show all registered entries on container');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $output->writeln(print_r($container, true));
    }
}