<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Application;

use DI\ContainerBuilder;
use League\Flysystem\Filesystem;
use Silly\Edition\PhpDi\Application;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Gin0115\WpScoper\Patcher\Compiler\PatcherFileIO;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherCacheRepository;

class WpScoper extends Application
{
    private bool $fromMenu = false;

    /**
     * Get if the command is called from within a menu
     *
     * @return bool
     */
    public function isFromMenu(): bool
    {
        return $this->fromMenu;
    }

    /**
     * Set if the command is called from within a menu
     *
     * @param bool $fromMenu
     * @return WpScoper
     */
    public function setFromMenu(bool $fromMenu): WpScoper
    {
        $this->fromMenu = $fromMenu;
        return $this;
    }


    protected function createContainer()
    {
        $container = ( new ContainerBuilder() )
            ->addDefinitions(
                [
                    Application::class => $this,
                    Filesystem::class => new Filesystem(
                        new LocalFilesystemAdapter(dirname(__DIR__, 2))
                    ),
                    PatcherCacheRepository::class => \DI\autowire()
                        ->constructorParameter('path', 'patchers'),
                    PatcherFileIO::class => \DI\autowire()
                        ->constructorParameter('patchPath', 'patchers')
                        ->constructorParameter('basePath', dirname(__DIR__, 2)),
                ]
            )
            ->build();
            return $container;
    }
}
