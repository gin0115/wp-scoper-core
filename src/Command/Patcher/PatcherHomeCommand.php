<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Patcher;

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Gin0115\WpScoper\Application\WpScoper;
use Gin0115\WpScoper\Command\AbstractMenuCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

class PatcherHomeCommand extends AbstractMenuCommand
{
/** @inheritDoc */
    protected static $defaultDescription = 'Main menu for the patcher options.';

    /**
     * Access to the application
     *
     * @var WpScoper
     */
    protected Application $app;


    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     */
    public function __construct(Application $app)
    {
        /** @var WpScoper $app */
        $this->app = $app;
        $this->app->setFromMenu(true);
    }

    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * The menu items to be displayed.
     *
     * @return @var array{key:string,title:string,description:string, action:class-string}[]
     */
    public function getTableItems(): array
    {
        return [
        ['Alias' => 'a', 'Command' => 'patcher:all', 'Description' => 'Lists all generate patcher definitions', 'action' => 'patcher:all'],
        ['Alias' => 'n', 'Command' => 'patcher:new', 'Description' => 'Create a new patcher definition from a stub file.', 'action' => 'patcher:new'],
        ['Alias' => 'd', 'Command' => 'patcher:delete', 'Description' => 'Removes a patcher definition from local', 'action' => 'patcher:delete'],
        ['Alias' => 'h', 'Command' => 'home', 'Description' => 'Back to main menu', 'action' => 'home'],
        ['Alias' => 'q', 'Command' => 'quit', 'Description' => 'Quit WP Scoper.', 'action' => 'test'],
        ];
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
        StyleHelper::commandHeader($style, ['Home','Patcher'], true);
    }
}
