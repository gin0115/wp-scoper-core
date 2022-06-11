<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Status;

use Silly\Command\Command;
use Silly\Input\InputArgument;
use Silly\Edition\PhpDi\Application;
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
     * @var \Silly\Edition\PhpDi\Application
     */
    private Application $app;

    private string $currentPath;
    private string $appPath;

    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app         = $app;
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
            return $this->returnToHome($style, $output);
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
            return $this->returnToHome($style, $output);
        }


        // Render the config.
        $this->renderProjectConfig($style, $configCompiler->getConfig());

        return $this->returnToHome($style, $output);
    }

    /**
     * Renders the return to home prompt.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    private function returnToHome(SymfonyStyle $style, OutputInterface $output): int
    {
        // Render the return to home prompt.
        $returnHome = $style->confirm('Return to status?', false);

        if ($returnHome) {
            $this->app->run(new ArrayInput(array( 'command' => 'status' )), $output);
        }

        return 0;
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
        $style->writeln('    <fg=cyan;bg=gray> Namespace Prefix:</> <fg=blue;bg=gray>' . $config->getNamespacePrefix() . '</>');
        $style->writeln('    <fg=cyan;bg=gray> Autoloader Prefix:</> <fg=blue;bg=gray>' . $config->getAutoloadPrefix() . '</>');
        $style->writeln('    <fg=cyan;bg=gray> Project Path:</> <fg=blue;bg=gray>' . $config->getProjectPath() . '</>');
        $style->writeln('    <fg=cyan;bg=gray> Project Vendor Path:</> <fg=blue;bg=gray>' . $config->getVendorPath() . '</>');
        $style->writeln('    <fg=cyan;bg=gray> Project Build Path:</> <fg=blue;bg=gray>' . $config->getBuildPath() . '</>');

        $style->block('Finders', null, 'fg=white;bg=gray', '    ', true);
        foreach ($config->getSourceFinders() as $finder) {
            $style->writeln('    <fg=cyan;bg=gray>Finder::create()</>');
			foreach ($finder as $method => $args) {
                // Cast the args as a string.
                $args = implode(', ', array_map('json_encode', $args));
                $args = str_replace('\\\\', '\\', $args);
                $style->writeln('    <fg=cyan;bg=gray>   ->' . $method . '( </><fg=blue;bg=gray>' . $args . '</><fg=cyan;bg=gray> )  </>');
            }
        }

        $style->block('Stubs & Patchers', null, 'fg=white;bg=gray', '    ', true);
dump($config);
		

    }
}
