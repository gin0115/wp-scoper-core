<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher;

use Exception;
use LogicException;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\FilesystemException;
use Gin0115\WpScoper\Patcher\Compiler\PatcherCreator;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherModel;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherCacheRepository;

class PatcherManager
{
    private PatcherCreator $patcherCreator;
    private PatcherCacheRepository $cacheRepository;

    public function __construct(PatcherCreator $patcherCreator, PatcherCacheRepository $cacheRepository)
    {
        $this->patcherCreator = $patcherCreator;
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * Checks if a patcher file has been logged with the same name and version
     *
     * @param string $name
     * @param string $version
     * @return bool
     */
    public function patcherExists(string $name, string $version): bool
    {
        $exists = $this->cacheRepository->filter(function (PatcherModel $patcher) use ($name, $version) {
            return $patcher->getName() === $name && $patcher->getVersion() === $version;
        });

        return count($exists) > 0;
    }

    /**
     * Creates a new patcher file
     *
     * @param string $source
     * @param string $name
     * @param string $version
     * @return PatcherModel
     * @throws Exception
     * @throws LogicException
     * @throws UnableToWriteFile
     * @throws FilesystemException
     */
    public function createPatcher(
        string $source,
        string $name,
        string $version
    ): PatcherModel {

        // Create the patcher file.
        try {
            $patcherPath = $this->patcherCreator->createPatcher($source, $name, $version);
        } catch (\Throwable $th) {
            // If the patcher file could not be created, throw an exception.
            throw new Exception(
                sprintf(
                    'Could not create patcher file, failed with following errors: %s | %s',
                    $th->getMessage(),
                    join(' | ', $this->creatorLog('error'))
                )
            );
        }
 
        // Compose as a model.
        $patcher = new PatcherModel(
            $this->patcherKeyCreator($name, $version),
            $name,
            $patcherPath,
            $version
        );

        // Add model to cache.
        $this->cacheRepository->add($patcher)->save();
// dump($this->cacheRepository,$patcher);
        return $patcher;
    }

    /**
     * Compiles a unique key for the patcher based on the name and version
     *
     * @param string $name
     * @param string $version
     * @return string
     */
    public function patcherKeyCreator(string $name, string $version): string
    {
        // Replace all whitespace with underscores from name and version
        $patcherKey = str_replace(' ', '_', trim($name)) . '_' . str_replace(' ', '_', trim($version));

        // Return the kwy as a key, but only allow letters, numbers, periods and underscores.
        return preg_replace('/[^a-zA-Z0-9._]/', '', $patcherKey);
    }

    /**
     * Access the creator log.
     *
     * @param string|null $mode if passed as null will return all log entries.
     * @return array{mode:string, message:string}[]
     */
    public function creatorLog(?string $mode = null): array
    {
        return null === $mode ?
            $this->patcherCreator->getLog()
            : array_filter(
                $this->patcherCreator->getLog(),
                fn(array $log): bool => $log['mode'] === $mode
            );
    }
}
