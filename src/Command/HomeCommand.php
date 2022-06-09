<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HomeCommand extends Command
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Home command for WP Scoper.';

    /**
     * The menu items
     *
     * @var array{key:string,title:string,description:string, action:class-string}[]
     */
    private $menuItems = [
        ['Alias' => 's', 'Command' => 'status', 'Description' => 'Show status of WP Scoper.', 'action' => 'status'],
        ['Alias' => 'n', 'Command' => 'new', 'Description' => 'Sets up a new WP Scoper project', 'action' => 'test'],
        ['Alias' => 'h', 'Command' => 'help', 'Description' => 'Show Help', 'action' => 'test'],
        ['Alias' => 'q', 'Command' => 'quit', 'Description' => 'Quit WP Scoper.', 'action' => 'test'],
    ];

    /**
     * Access to the application
     *
     * @var \Silly\Edition\PhpDi\Application
     */
    private Application $app;

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

    /**
     * Execute the home/menu command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $style->writeln(StyleHelper::getLogo());
        $style->table(
            ['Alias', 'Command', 'Description'],
            $this->getMenuItemsForTable()
        );


        $value = $this->awaitMenuInput($style);

        // If quit
        if ($value === 'q' || $value === 'quit') {
            return 0;
        }

        // Get the action from the menu item
        $action = $this->getActionFromCommand($value);

        // Run the action
		dump($action);
        return $this->app->runCommand($action);

        return 0;
    }

    /**
     * Returns the menu items without the action
     * for use in menu table
     *
     * @return array{key:string,title:string,description:string}[]
     */
    private function getMenuItemsForTable(): array
    {
        return array_map(
            function ($item) {
                return [
                    'key' => $item['Alias'],
                    'title' => $item['Command'],
                    'Description' => $item['Description'],
                ];
            },
            $this->menuItems
        );
    }

    /**
     * Recursively awaits input from the user until a valid menu item is selected.
     *
     * @param SymfonyStyle $style
     * @return string
     */
    private function awaitMenuInput(SymfonyStyle $style): string
    {
        $value = $style->ask('Please enter a key or a command and press enter.', 'quit');

        if (
            !in_array($value, \array_column($this->menuItems, 'Alias'))
            && !in_array($value, \array_column($this->menuItems, 'Command'))
        ) {
            $style->error('Invalid input');
            return $this->awaitMenuInput($style);
        }

        return $value;
    }

    /**
     * Get the action from the menu item
     *
     * @param string $value
     * @return string
     * @throws InvalidArgumentException if Command not found using command or alias.
     */
    private function getActionFromCommand(string $command)
    {
        // get the command from the menuItems based on alias or command
        $command = \array_filter($this->menuItems, function ($item) use ($command) {
            return $item['Alias'] === $command || $item['Command'] === $command;
        });

        // Throw exception if command not found
        if (empty($command)) {
            throw new \InvalidArgumentException('Command not found');
        }

        // get the action from the command
        $action = \array_values($command)[0]['action'];

        return $action;
    }
}
