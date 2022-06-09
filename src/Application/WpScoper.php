<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Application;

use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application;

class WpScoper extends Application
{
    protected function createContainer()
    {
        $container = ( new ContainerBuilder() )
            ->addDefinitions(
                [
                    Application::class => $this
                ]
            )
            ->build();
            return $container;
    }
}
