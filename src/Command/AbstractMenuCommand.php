<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractMenuCommand extends Command
{

    /**
     * Get an instance ofthe application, this MUST be injected via constructor
     *
     * @var \Silly\Edition\PhpDi\Application
     */
    abstract protected function getApp(): Application;

    /**
     * The menu items to be displayed.
     *
     * @return @var array{key:string,title:string,description:string, action:class-string}[]
     */
    abstract public function getTableItems(): array;

    /**
     * The prompt to be displayed.
     *
     * @return string
     */
    abstract public function getPrompt(): string;

    /**
     * Default command called if no command is specified.
     *
     * @return string
     */
    abstract public function getDefault(): string;

    /**
     * Displayed before the menu is rendered
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return void
     */
    public function beforeMenu(SymfonyStyle $style): void
    {
        // Do nothing
    }

    /**
     * Displayed after the menu is rendered, but before the user is prompted for input.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return void
     */
    public function afterMenu(SymfonyStyle $style): void
    {
        // Do nothing
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

        $this->beforeMenu($style);
        $style->table(
            ['Alias', 'Command', 'Description'],
            $this->getMenuItemsForTable()
        );
        $this->afterMenu($style);

        $value = $this->awaitMenuInput($style);

        // If quit
        if ($value === 'q' || $value === 'quit') {
            return 0;
        }

        // Get the action from the menu item
        $action = $this->getActionFromCommand($value);

        // Run the action
        return $this->getApp()->run(new ArrayInput(array( 'command' => $action )), $output);
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
            $this->getTableItems()
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
            !in_array($value, \array_column($this->getTableItems(), 'Alias'))
            && !in_array($value, \array_column($this->getTableItems(), 'Command'))
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
        $command = \array_filter($this->getTableItems(), function ($item) use ($command) {
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
