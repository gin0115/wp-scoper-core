<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Gin0115\WpScoper\Application\WpScoper;
use Gin0115\WpScoper\Command\AbstractMenuCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HomeCommand extends AbstractMenuCommand
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Home command for WP Scoper.';

    /**
     * Access to the application
     *
     * @var \Silly\Edition\PhpDi\Application
     */
    private WpScoper $app;

    /**
     * Creates an instance of the HomeCommand
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        /** @var WpScoper $app */
        $this->app = $app;
        $this->app->setFromMenu(true);
        parent::__construct();
    }

    /**
     * The menu items to be displayed.
     *
     * @return @var array{key:string,title:string,description:string, action:class-string}[]
     */
    public function getTableItems(): array
    {
        return [
        ['Alias' => 's', 'Command' => 'status', 'Description' => 'Show status of WP Scoper.', 'action' => 'status'],
        ['Alias' => 'p', 'Command' => 'patcher', 'Description' => 'Global patchers, list, add and remove', 'action' => 'patcher'],
        ['Alias' => 'n', 'Command' => 'new', 'Description' => 'Sets up a new WP Scoper project', 'action' => 'test'],
        ['Alias' => 'q', 'Command' => 'quit', 'Description' => 'Quit WP Scoper.', 'action' => 'test'],
        ];
    }

    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * The prompt to be displayed.
     *
     * @return string
     */
    public function getPrompt(): string
    {
        return 'Please enter the command or alias of the menu item you want to execute: ';
    }

    /**
     * Default command called if no command is specified.
     *
     * @return string
     */
    public function getDefault(): string
    {
        return 'quit';
    }

    /**
     * Displayed before the menu is rendered
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return void
     */
    public function beforeMenu(SymfonyStyle $style): void
    {
        StyleHelper::commandHeader($style, ['Home'], true);
    }
}
