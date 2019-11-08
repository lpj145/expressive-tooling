<?php
/**
 * Date: 08/11/2019
 * @see       https://github.com/lpj145/expressive-tooling for the canonical source repository
 * @license   https://github.com/lpj145/expressive-tooling/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

use PackageVersions\Versions;

$cwd = getcwd();
require $cwd.'/vendor/autoload.php';

$container = null;
$version = strstr(Versions::getVersion('mdantas/expressive-tooling'), '@', true);

if (file_exists($cwd.'/config/container.php')) {
    $container = require $cwd.'/config/container.php';
}

if (null !== $container && ! $container instanceof \Psr\Container\ContainerInterface) {
    throw new ErrorException(sprintf('%s%s file is not a valid psr-11 container.', $cwd, '/config/container.php'));
}

(new \mdantas\Expressive\Tooling\ToolingApplication($container, $version))
->run();
