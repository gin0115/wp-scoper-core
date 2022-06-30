<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command\Patcher;

use Silly\Command\Command;
use Gin0115\WpScoper\Helper\MenuHelper;
use Gin0115\WpScoper\Helper\StyleHelper;
use Symfony\Component\Console\Helper\Table;
use PinkCrab\FunctionConstructors\Arrays as Arr;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherModel;
use Symfony\Component\Console\Output\OutputInterface;
use PinkCrab\FunctionConstructors\GeneralFunctions as G;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherCacheRepository;

class PatcherDeleteCommand extends Command
{
    /** @inheritDoc */
    protected static $defaultDescription = 'Delete a patcher from cache';

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
        StyleHelper::commandHeader($style, ['Home','Patcher', 'Delete'], true);

        $table = new Table($output);
        $table
            ->setHeaders(['Number', 'Name', 'Version'])
            ->setRows($this->getPatchers());
        $table->setHorizontal(false);
        $table->render();

        // Get patcher to remove.
        $patcherNumber = $this->promptWhichPatcherToRemove($style);
        $patcher = $this->getPatcherFromIndex(($patcherNumber - 1));

        dd($patcherNumber, $patcher);

        return $this->menuHelper->returnToMenu('patcher');
    }

    /**
     * Prompts the user for the number of the patcher to remove.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @return int
     */
    private function promptWhichPatcherToRemove(SymfonyStyle $style): int
    {
        $patcherCount = count($this->getPatchers());
        $patcherNumber = $style->ask('Which patcher would you like to delete (please enter number from table above)?', (string) $patcherCount);
        if ($patcherNumber < 1 || $patcherNumber > $patcherCount) {
            $style->error('Invalid patcher number.');
            return $this->promptWhichPatcherToRemove($style);
        }
        return (int) $patcherNumber;
    }


    /**
     * Get all patchers from JSON file
     *
     * @return array
     */
    private function getPatchers(): array
    {
        $count = 0;
        return Arr\map(function (PatcherModel $patcher) use (&$count): array {
            $count++;
            return [
                $count,
                $patcher->getName(),
                $patcher->getVersion(),
            ];
        })($this->patcherCacheRepository->getAll());
    }

    /**
     * Get a patcher based on the index
     *
     * @param int $index
     * @return \Gin0115\WpScoper\Patcher\Dictionary\PatcherModel
     */
    private function getPatcherFromIndex(int $index): PatcherModel
    {
        return $this->patcherCacheRepository->getAll()[$index ];
    }

    public function deletePatcher(PatcherModel $patcher): void
    {
        // Remove from log
        $this->patcherCacheRepository->remove($patcher->getKey());
        // Remove from FS
    }
}
