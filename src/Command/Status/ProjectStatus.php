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

class ProjectStatus extends Command
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
        $this->app = $app;
        $this->currentPath = getcwd();
        $this->appPath = dirname(__DIR__, 2);
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'project',
            InputArgument::REQUIRED,
            'The base path of the project'
        )
        ;
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
        // Get project path from argument, or use fallback of called path.
        $projectPath = $input->getArgument('project');

        // Check if project path is valid.
        if (!is_dir($projectPath)) {
            $style->error('Project path is not a valid directory.');
            return 1;
        }


        $style->writeln(StyleHelper::getLogo());
        $style->block('Project Config', null, 'info');

        // Attempt to load project config.
        $config = new ConfigCompiler($projectPath, $this->appPath);

        // If no config, prompt if one should be created.
        if (!$config->hasConfig()) {
            $style->warning(['No config found.',
            'Please run `wp-scoper new` to create a new config.']);
            return 1;
        }

        $r = $config->getConfig();

        $style->block('Config', null, 'fg=white;bg=bright-magenta', '    ', true);
        $style->writeln('    <fg=black;bg=bright-magenta>Namespace Prefix:</> <fg=black;bg=white>' . $r->getNamespacePrefix() . '</>');
        $style->writeln('    <fg=white;bg=bright-magenta>Autoloader Prefix:</> <fg=black;bg=white>' . $r->getAutoloadPrefix() . '</>');

        $style->block('Paths', null, 'fg=white;bg=bright-magenta', '    ', true);
        $style->writeln('    <fg=white;bg=bright-magenta>Project Path:</> <fg=black;bg=white>' . $r->getProjectPath() . '</>');
        $style->writeln('    <fg=white;bg=bright-magenta>Project Vendor Path:</> <fg=black;bg=white>' . $r->getVendorPath() . '</>');
        $style->writeln('    <fg=white;bg=bright-magenta>Project Build Path:</> <fg=black;bg=white>' . $r->getBuildPath() . '</>');

        $style->block('Finders', null, 'fg=white;bg=bright-magenta', '    ', true);
        foreach ($r->getSourceFinders() as $finder) {
            foreach ($finder as $key => $value) {
                $value = is_array($value) ? implode(', ', $value) : $value;
                $value = is_bool($value) ? ($value ? 'TRUE' : 'FALSE') : $value;
                $style->writeln('    <fg=white;bg=bright-magenta>' . $key . ':</> <fg=black;bg=white>' . $value . '</>');
            }
        }
        // $style->writeln('    <fg=white;bg=bright-magenta>Config Path:</> <fg=black;bg=white>' . $r->getConfigPath() . '</>');
        // $style->writeln('    <fg=white;bg=bright-magenta>Config File:</> <fg=black;bg=white>' . $r->getConfigFile() . '</>');


        // $r = new ProjectConfig(
        //     'd',$this->currentPath,
        //     'd', 'DD\\DD', 'fff_fff', [], [], []
        // );

        // $config->updateConfig($r);


        // dump($config, $r, __DIR__, \getcwd());

        $home = $style->confirm('Return to main menu?', true);
        // \dump($home);
        if ($home) {
            $output->write(sprintf("\033\143"));
            return $this->app->run(new ArrayInput(['command' => 'home']), $output);
        }


        return 0;
    }
}
