<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Status;

use Silly\Command\Command;
use Silly\Input\InputArgument;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\MenuHelper;
use Gin0115\WpScoper\Helper\StyleHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Gin0115\WpScoper\ProjectConfig\ProjectConfig;
use Symfony\Component\Console\Style\SymfonyStyle;
use Gin0115\WpScoper\ProjectConfig\ConfigCompiler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProjectStatusCommand extends Command
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Lists the configuration of WP Scoper for the current project.';

    /**
     * Access to the application
     *
     * @var MenuHelper $menuHelper
     */
    private MenuHelper $menuHelper;

    private string $currentPath;
    private string $appPath;

    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     */
    public function __construct(MenuHelper $menuHelper)
    {
        $this->menuHelper = $menuHelper;
        $this->currentPath = getcwd();
        $this->appPath     = dirname(__DIR__, 2);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'project',
            InputArgument::REQUIRED,
            'The base path of the project'
        );
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

        // Render the header
        StyleHelper::commandHeader($style, ['Home','Status', 'Project'], true);

        // Get project path from argument, or prompt for it
        $projectPath = $input->getArgument('project');
        if (empty($projectPath)) {
             $projectPath = $style->ask('Please enter the path to the project', '.');
        }

        // If the project path is ./, then we need to use the current path
        if ($projectPath === '.') {
            $projectPath = $this->currentPath;
        }

        // Check if project path is valid.
        if (! is_dir($projectPath)) {
            $style->error('Project path is not a valid directory.');
            return $this->menuHelper->returnToMenu('status');
        }

        // Attempt to load project config.
        $configCompiler = new ConfigCompiler($projectPath, $this->appPath);

        // If no config, prompt if one should be created.
        if (! $configCompiler->hasConfig()) {
            $style->warning(
                array(
                    'No config found.',
                    'Please run `wp-scoper new` to create a new config.',
                )
            );
            return $this->menuHelper->returnToMenu('status');
        }


        // Render the config.
        $this->renderProjectConfig($style, $configCompiler->getConfig());

        return $this->menuHelper->returnToMenu('status');
    }

    /**
     * Renders the project config.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @param \Gin0115\WpScoper\ProjectConfig\ProjectConfig $config
     */
    private function renderProjectConfig(SymfonyStyle $style, ProjectConfig $config): void
    {
        $style->block('Config', null, 'fg=white;bg=gray', '    ', true);

        $style->horizontalTable(
            [
                'Namespace Prefix',
                'Autoloader Prefix',
                'Project Path',
                'Project Vendor Path',
                'Project Build Path',
            ],
            [[
                $config->getNamespacePrefix(),
                $config->getAutoloadPrefix(),
                $config->getProjectPath(),
                $config->getVendorPath(),
                $config->getBuildPath(),
            ]]
        );

        $style->block('Finders', null, 'fg=white;bg=gray', '    ', true);
        foreach ($config->getSourceFinders() as $finder) {
            $style->writeln('    <fg=cyan;bg=black>Finder::create()</>');
            foreach ($finder as $method => $args) {
                // Cast the args as a string.
                $args = implode(', ', array_map('json_encode', $args));
                $args = str_replace('\\\\', '\\', $args);
                $style->writeln('    <fg=cyan;bg=black>   ->' . $method . '( </><fg=blue;bg=black>' . $args . '</><fg=cyan;bg=black> )  </>');
            }
        }

        $style->block('Stubs & Patchers', null, 'fg=white;bg=gray', '    ', true);

        $style->table(
            ['Stubs'],
            array_map(fn($e) => [$e], $config->getPatcherStubs())
        );

        $style->table(
            ['Ignored Namespaces'],
            array_map(fn($e) => [$e], $config->getIgnoreNamespaces())
        );

        $style->table(
            ['Ignored Symbols'],
            array_map(fn($e) => [$e], $config->getPatcherSymbols())
        );
    }
}
