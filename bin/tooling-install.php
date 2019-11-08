<?php

function copyToolToRoot($rootDir) {
    $templateFile = dirname(__FILE__).'/../template/expressive.php';
    $initAppString = <<<EOT
        #!/usr/bin/env php
        <?php
        EOT;

    $appTemplate = file_get_contents($templateFile);
    $appTemplate = $initAppString . $appTemplate;

    file_put_contents($rootDir.'app', $appTemplate);
}

$rootDir = dirname(__FILE__);
$rootDir = realpath($rootDir.'/../../../../');

echo 'Copying app to project root!'.PHP_EOL;
copyToolToRoot($rootDir);
echo 'app tool created with success!'.PHP_EOL;
echo 'For windows users `php app %args`'.PHP_EOL;
