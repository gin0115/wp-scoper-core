<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Application;

use DI\ContainerBuilder;
use League\Flysystem\Filesystem;
use Silly\Edition\PhpDi\Application;
use League\Flysystem\Local\LocalFilesystemAdapter;

class WpScoper extends Application
{
    protected function createContainer()
    {
        $container = ( new ContainerBuilder() )
            ->addDefinitions(
                [
                    Application::class => $this,
                    Filesystem::class => new Filesystem(
                        new LocalFilesystemAdapter(dirname(__DIR__, 2))
                    ),
                ]
            )
            ->build();
            return $container;
    }
}
