<?php
/**
 * Date: 08/11/2019
 * @see       https://github.com/lpj145/expressive-tooling for the canonical source repository
 * @license   https://raw.githubusercontent.com/lpj145/expressive-tooling/master/LICENSE MIT License
 */
declare(strict_types=1);

namespace mdantas\Expressive\Tooling;

use mdantas\Expressive\Tooling\Commands\DebugConfig;
use mdantas\Expressive\Tooling\Commands\DebugContainer;
use mdantas\Expressive\Tooling\Commands\DebugRoutes;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Tooling\Factory;
use Zend\Expressive\Tooling\Module;
use Zend\Expressive\Tooling\CreateMiddleware;
use Zend\Expressive\Tooling\CreateHandler;
use Zend\Expressive\Tooling\MigrateInteropMiddleware;
use Zend\Expressive\Tooling\MigrateMiddlewareToRequestHandler;
use Symfony\Component\Console\Application;

class ToolingApplication extends Application
{
    const DEFAULT_NAME = 'expressive';
    /**
     * @var ContainerInterface
     */
    private $container;
    public function __construct(
        ContainerInterface $container = null,
        string $version = 'UNKNOWN',
        string $name = self::DEFAULT_NAME
    ) {
        parent::__construct($name, $version);
        $this->container = $container;
        $this->addCommands([
            new Factory\CreateFactoryCommand('factory:create'),
            new CreateMiddleware\CreateMiddlewareCommand('middleware:create'),
            new MigrateInteropMiddleware\MigrateInteropMiddlewareCommand('migrate:interop-middleware'),
            new MigrateMiddlewareToRequestHandler\MigrateMiddlewareToRequestHandlerCommand(
                'migrate:middleware-to-request-handler'
            ),
            new Module\CreateCommand('module:create'),
            new Module\DeregisterCommand('module:deregister'),
            new Module\RegisterCommand('module:register'),
        ]);

        if (null !== $container) {
            $this->addCommands([
                new CreateHandler\CreateHandlerCommand('action:create'),
                new CreateHandler\CreateHandlerCommand('handler:create')
            ]);

            $this->addCommands([
                new DebugRoutes('debug:routes', $container),
                new DebugConfig('debug:config', $container),
            ]);

            $this->registerExtensibleCommands($container);
        }

    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function hasContainer(): bool
    {
        return !is_null($this->container);
    }

    protected function registerExtensibleCommands(ContainerInterface $container): void
    {
        $commands = $container->get('config')['commands'] ?? [];

        foreach ($commands as $commandName => $command) {
            if (!$command instanceof \Symfony\Component\Console\Command\Command) {
                throw new \ErrorException('Command:'.$commandName.' on config:commands are not a valid \Symfony\Component\Console\Command\Command.');
            }

            $this->addCommands(new $command($commandName));
        }
    }
}