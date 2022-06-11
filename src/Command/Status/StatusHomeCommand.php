<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Status;

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Gin0115\WpScoper\Command\AbstractMenuCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

class StatusHomeCommand extends AbstractMenuCommand
{

/** @inheritDoc */
    protected static $defaultDescription = 'Checks the configuration of WP Scoper for the current project.';
    
    /**
     * Access to the application
     *
     * @var \Silly\Edition\PhpDi\Application
     */
    protected Application $app;


    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct();
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
        ['Alias' => 'p', 'Command' => 'status:project', 'Description' => 'Show the status and config of a projects WP Scoper setup', 'action' => 'status:project'],
        ['Alias' => 'g', 'Command' => 'status:global', 'Description' => 'Show status about the WP Scoper installation', 'action' => 'status:global'],
        ['Alias' => 'g', 'Command' => 'status:commands', 'Description' => 'Shows a list of all commands and arguments', 'action' => 'status:global'],
        ['Alias' => 'b', 'Command' => 'home', 'Description' => 'Back to main menu', 'action' => 'home'],
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
        StyleHelper::commandHeader($style, ['Home','Status'], true);
    }
}
