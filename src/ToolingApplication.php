<?php
/**
 * Date: 08/11/2019
 * @see       https://github.com/lpj145/expressive-tooling for the canonical source repository
 * @license   https://github.com/lpj145/expressive-tooling/blob/master/LICENSE.md MIT License
 */
declare(strict_types=1);

namespace mdantas\Expressive\Tooling;

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
        }
    }
}