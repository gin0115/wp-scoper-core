<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Command\HomeCommand;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application( 'WP Scoper', '0.0.1' );

$app->command( 'home', array( HomeCommand::class, 'execute' ) );
$app->setDefaultCommand( 'home' );
$app->run();
