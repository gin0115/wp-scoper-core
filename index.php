<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Command\HomeCommand;
use Gin0115\WpScoper\Application\WpScoper;
use Gin0115\WpScoper\Command\StatusCommand;
use Gin0115\WpScoper\Command\Status\StatusHomeCommand;
use Gin0115\WpScoper\Command\Patcher\PatcherAllCommand;
use Gin0115\WpScoper\Command\Patcher\PatcherNewCommand;
use Gin0115\WpScoper\Command\Patcher\PatcherHomeCommand;
use Gin0115\WpScoper\Command\Status\ProjectStatusCommand;
use Gin0115\WpScoper\Command\Patcher\PatcherDeleteCommand;

require_once __DIR__ . '/vendor/autoload.php';

$app = new WpScoper('WP Scoper', '0.0.1');

$app->command('test', function () {
    echo 'test';
});

$app->command('home', array( HomeCommand::class, 'execute' ))
    ->descriptions(HomeCommand::getDefaultDescription())
    ->setHelp('You can use this command to see a list of all available commands, with a basic menu system');

/** Status */
$app->command('status', array( StatusHomeCommand::class, 'execute' ))
    ->descriptions(StatusHomeCommand::getDefaultDescription())
    ->setHelp('Get the status of WP Scoper for projects and globally');

$app->command('status:project [project]', array( ProjectStatusCommand::class, 'execute' ))
    ->descriptions(StatusHomeCommand::getDefaultDescription())
    ->setHelp('Get the status of WP Scoper for projects and globally');

/** Patcher */
$app->command('patcher', array( PatcherHomeCommand::class, 'execute' ))
    ->descriptions(PatcherHomeCommand::getDefaultDescription())
    ->setHelp('Handle the defined patchers, generated from stubs');

$app->command('patcher:all', array( PatcherAllCommand::class, 'execute' ))
    ->descriptions(PatcherAllCommand::getDefaultDescription())
    ->setHelp('Lists all Patchers defined for WP Scoper');

$app->command('patcher:delete', array( PatcherDeleteCommand::class, 'execute' ))
    ->descriptions(PatcherDeleteCommand::getDefaultDescription())
    ->setHelp('Allows the user to select which patcher to delete');
    
$app->command('patcher:new [path] [name] [version]', array( PatcherNewCommand::class, 'execute' ))
    ->descriptions(PatcherNewCommand::getDefaultDescription())
    ->setHelp('Adds a new patcher to WP Scoper from a stub');
$app->setDefaultCommand('home');
$app->run();
