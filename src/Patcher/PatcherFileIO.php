<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher;

use League\Flysystem\Filesystem;

class PatcherFileIO
{
    private string $patchPath;

    private Filesystem $filesystem;

    public function __construct(string $patchPath, Filesystem $filesystem)
    {
        $this->patchPath = $patchPath;
        $this->filesystem = $filesystem;
    }

    /**
     * Writes a file to the patcher directory.
     *
     * @param string $fileName
     * @param mixed $content
     * @return void
     */
    public function write(string $fileName, $content): void
    {
        $this->filesystem->write($this->patchPath . \DIRECTORY_SEPARATOR . $fileName, \serialize($content));
    }


    /**
     * Reads a file from the patcher directory.
     *
     * @param string $fileName
     * @return mixed
     */
    public function read(string $fileName)
    {
        return \unserialize($this->filesystem->read($this->patchPath . \DIRECTORY_SEPARATOR . $fileName));
    }

    /**
     * Checks if a file exists in the patcher directory.
     *
     * @param string $fileName
     * @return bool
     */
    public function exists(string $fileName): bool
    {
        return $this->filesystem->has($this->patchPath . \DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     * Deletes a file from the patcher directory.
     *
     * @param string $fileName
     * @return void
     */
    public function delete(string $fileName): void
    {
        $this->filesystem->delete($this->patchPath . \DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     * Deletes all files from the patcher directory.
     *
     * @return void
     */
    public function deleteAll(): void
    {
        $this->filesystem->deleteDirectory($this->patchPath);
    }

    /**
     * Returns the patcher directory path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->patchPath;
    }


}
