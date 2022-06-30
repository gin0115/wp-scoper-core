<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Helper;

use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Application\WpScoper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class MenuHelper
{
    /**
     * @var WpScoper
     */
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Output to the console.
     *
     * @return OutputInterface
     */
    private function getOutput(): OutputInterface
    {
        return new ConsoleOutput();
    }

    /**
     * Access the ArgvInput.
     *
     * @return InputInterface
     */
    private function getInput(): InputInterface
    {
        return new ArgvInput();
    }

    /**
     * Get the SymfonyStyle.
     *
     * @return SymfonyStyle
     */
    private function getStyle(): SymfonyStyle
    {
        return new SymfonyStyle($this->getInput(), $this->getOutput());
    }

        /**
     * Renders the return to home prompt.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function returnToMenu(string $command, ?string $message = null): int
    {
        // If not accessed after a menu page, bail out.
        if (!$this->app->isFromMenu()) {
            return 0;
        }

        // Set a default message if not defined.
        $message = $message ?? \sprintf('Return to %s menu?', $command);

        // Render the return to home prompt.
        $returnHome = $this->getStyle()->confirm($message, false);

        // Return home if confirmed.
        if ($returnHome) {
            $this->app->run(new ArrayInput(array( 'command' => 'patcher' )), $this->getOutput());
        }

        return 0;
    }
}
