<?php
/**
 * Created by PhpStorm.
 * User: Marquinho
 * Date: 08/11/2019
 * Time: 17:39
 */

namespace mdantas\Expressive\Tooling;


class Installer
{
    const APP_NAME = 'app';

    public static function postInstall(\Composer\Script\Event $event)
    {
        $io = $event->getIO();
        $rootDir = dirname(__FILE__);
        $rootDir = realpath($rootDir.'/../../../../');

        $io->write('Copying app to project root!');
        static::copyToolToRoot($rootDir);
        $io->write('app tool created with success!');
        $io->write('For windows users `php app %args`');

    }

    public static function copyToolToRoot(string $rootDir)
    {
        $templateFile = dirname(__FILE__).'/../bin/expressive.php';
        $initAppString = <<<EOT
        #!/usr/bin/env php
<?php
        EOT;

        $appTemplate = file_get_contents($templateFile);

        file_put_contents($rootDir.'app', $initAppString . $appTemplate);
    }
}