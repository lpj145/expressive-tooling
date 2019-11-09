<?php
/**
 * Date: 08/11/2019
 * @see       https://github.com/lpj145/expressive-tooling for the canonical source repository
 * @license   https://raw.githubusercontent.com/lpj145/expressive-tooling/master/LICENSE MIT License
 */

namespace mdantas\Expressive\Tooling\Commands;


use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Expressive\Router\Route;

class DebugRoutes extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Show all registered routes on application!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        /** @var \Zend\Expressive\Application $app */
        $app = $container->get(\Zend\Expressive\Application::class);

        $appRoutes = $app->getRoutes();
        $tableRoutes = new Table($output);
        $listRoutes = array_map(function(Route $route){
            return [
                $route->getName(),
                $route->getPath(),
                '['.implode(',', $route->getAllowedMethods()).']'
            ];
        }, $appRoutes);

        $tableRoutes->setHeaders([
            'name',
            'url',
            'methods'
        ]);

        $tableRoutes->addRows($listRoutes);
        $tableRoutes->render();
        $output->writeln('Container have '.count($listRoutes).' registered routes!');
    }
}