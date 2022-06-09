<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Command\HomeCommand;
use Gin0115\WpScoper\Application\WpScoper;
use Gin0115\WpScoper\Command\StatusCommand;

require_once __DIR__ . '/vendor/autoload.php';

$app = new WpScoper('WP Scoper', '0.0.1');

$app->command('test', function () {
    echo 'test';
});

$app->command('home', array( HomeCommand::class, 'execute' ))
    ->descriptions(HomeCommand::getDefaultDescription())
    ->setHelp('You can use this command to see a list of all available commands, with a basic menu system');

$app->command('status [project]', array( StatusCommand::class, 'execute' ))
    ->descriptions(StatusCommand::getDefaultDescription())
    ->setHelp('Used to check the WP Scoper config of the current project');

$app->setDefaultCommand('home');
$app->run();
