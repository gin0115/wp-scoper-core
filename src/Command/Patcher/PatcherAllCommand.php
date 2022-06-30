<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Patcher;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Gin0115\WpScoper\Helper\MenuHelper;
use Gin0115\WpScoper\Helper\StyleHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArrayInput;
use PinkCrab\FunctionConstructors\Arrays as Arr;
use Gin0115\WpScoper\Command\AbstractMenuCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherModel;
use Symfony\Component\Console\Output\OutputInterface;
use PinkCrab\FunctionConstructors\GeneralFunctions as G;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherCacheRepository;

class PatcherAllCommand extends Command
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Lists all patchers defined for WP Scoper.';

    private PatcherCacheRepository $patcherCacheRepository;

    private MenuHelper $menuHelper;


    public function __construct(PatcherCacheRepository $patcherCacheRepository, MenuHelper $menuHelper)
    {
        $this->menuHelper = $menuHelper;
        $this->patcherCacheRepository = $patcherCacheRepository;
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

        // Render the header
        StyleHelper::commandHeader($style, ['Home','Patcher', 'All'], true);

        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Version', 'Path'])
            ->setRows($this->getPatchers());
        $table->setHorizontal(false);
        $table->render();

        return $this->menuHelper->returnToMenu('patcher');
    }

    /**
     * Get all patchers from JSON file
     *
     * @return array
     */
    private function getPatchers(): array
    {
        return Arr\map(function (PatcherModel $patcher): array {
            return [
                $patcher->getName(),
                $patcher->getVersion(),
                $patcher->getPath(),
            ];
        })($this->patcherCacheRepository->getAll());
    }
}
