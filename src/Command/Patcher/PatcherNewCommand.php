<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Patcher;

use Silly\Command\Command;
use Silly\Input\InputArgument;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\MenuHelper;
use Gin0115\WpScoper\Helper\QuestionTrait;
use Gin0115\WpScoper\Patcher\PatcherManager;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

class PatcherNewCommand extends Command
{
    use QuestionTrait;

    /** @inheritDoc */
    protected static $defaultDescription = 'Adds a new patcher to WP Scoper.';

    /**
     * Access to the application
     *
     * @var \Silly\Edition\PhpDi\Application
     */
    private Application $app;

    private MenuHelper $menuHelper;

    private PatcherManager $patcherManager;

    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     * @param MenuHelper $menuHelper;
     */
    public function __construct(Application $app, MenuHelper $menuHelper, PatcherManager $patcherManager)
    {
        /** @var WpScoper $app */
        $this->app         = $app;
        $this->menuHelper  = $menuHelper;
        $this->patcherManager = $patcherManager;
        parent::__construct();


    }

    protected function configure(): void
    {
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'The full path to the stub file'
        );

        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the stub'
        );

        $this->addArgument(
            'version',
            InputArgument::REQUIRED,
            'The version of the stub being used'
        );
    }

    /**
     * Execute the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        
        $stub = $this->getStubPath($input, $style);
        $name = $this->getStubName($input, $style);
        $version = $this->getStubVersion($input, $style);
        $patcherPath = $this->patcherManager->createPatcher($stub, $name, $version);

        $style->success(sprintf('Stub path: %s  ', $patcherPath->getPath()));



        return $this->menuHelper->returnToMenu('patcher');
    }

    /**
     * Gets the stub path, prompts if not defined.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return string
     */
    private function getStubPath(InputInterface $input, OutputInterface $output): string
    {
        $path = $input->getArgument('path');
        if (is_null($path)) {
            $path = $this->requestValidFileName('Enter the path to the stub file: ')(
                $input,
                $output
            );
        }

        return $path;
    }

    /**
     * Gets the name of the stub, prompts if not defined.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return string
     */
    private function getStubName(InputInterface $input, SymfonyStyle $style): string
    {
        $name = $input->getArgument('name');
        if (is_null($name)) {
            $name = $style->ask('Please enter the name of the stub');
        }

        // If name is empty, ask again
        if (empty($name)) {
            $name = $this->getStubName($input, $style);
        }

        return $name;
    }

    /**
     * Gets the version of the stub, prompts if not defined.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return string
     */
    private function getStubVersion(InputInterface $input, SymfonyStyle $style): string
    {
        $version = $input->getArgument('version');
        if (is_null($version)) {
            $version = $style->ask('Please enter the version of the stub');
        }

        // If version is empty, ask again
        if (empty($version)) {
            $version = $this->getStubVersion($input, $style);
        }

        return $version;
    }
}
