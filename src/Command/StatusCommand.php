<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command;

use Silly\Command\Command;
use Silly\Input\InputArgument;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\StyleHelper;
use Gin0115\WpScoper\ProjectConfig\ProjectConfig;
use Symfony\Component\Console\Style\SymfonyStyle;
use Gin0115\WpScoper\ProjectConfig\ConfigCompiler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Checks the configuration of WP Scoper for the current project.';

    /**
     * The menu items
     *
     * @var array{key:string,title:string,description:string, action:class-string}[]
     */
    private $menuItems = [
        ['Alias' => 's', 'Command' => 'status', 'Description' => 'Show status of WP Scoper.', 'action' => 'test'],
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

    private string $currentPath;

    /**
     * Creates an instance of the HomeCommand
     *
     * @param \Silly\Edition\PhpDi\Application $app
     */
    public function __construct(Application $app)
    {
        // $this->app = $app;
        $this->currentPath = getcwd();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
                'project',
                InputArgument::OPTIONAL,
                'The base path of the project',
                getcwd()
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

        $style->writeln(StyleHelper::getLogo());
        $style->writeln('<info>Project Config</info>');

        $project = $input->getArgument('project') ?? $this->currentPath;

        // If we have no project, throw an error
        if (!$project) {
            $style->error('No project path provided.');
            // return 1;
        }

        $config = new ConfigCompiler($project);

        // If no config, throw an error
        if (!$config->hasConfig()) {
            // @todo Create a new project here on optional request.
            $style->error('No config found.');



            // return 1;
        }

        $r = $config->getConfig();

        // $r = new ProjectConfig(
        //     'd',$this->currentPath,
        //     'd', 'DD\\DD', 'fff_fff', [], [], []
        // );

        $config->updateConfig($r);


        dump($config, $r);


        return 0;
    }
}
