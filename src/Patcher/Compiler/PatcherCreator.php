<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher\Compiler;

class PatcherCreator
{
    private IdentifierExtractor $identifierExtractor;

    private PatcherFileIO $fileIO;

    /** @var array{mode: string, message:string}[] */
    private array $log = [];

    public function __construct(
        PatcherFileIO $fileIO
    ) {
        $this->fileIO = $fileIO;
    }

    /**
     * Create a patcher file based on the given stub, name and version
     *
     * @param string $stubFile
     * @param string $name
     * @param string $version
     * @return string|null Path to the patcher file
     */
    public function createPatcher(
        string $stubFile,
        string $name,
        string $version
    ): ?string {
        // Attempt to extract the AST from the stub file.
        try {
            $extractor = new IdentifierExtractor();
            $extractor->addStubFile($stubFile);
            $patcherContent = $extractor->extract();
        } catch (\Throwable $th) {
            // If exception of thrown pass all details to the log as an error
            $this->addError(
                \sprintf(
                    'Error while extracting identifiers from stub file: %s, with error %s on %s:%d',
                    $stubFile,
                    $th->getMessage(),
                    $th->getFile(),
                    $th->getLine()
                ),
            );

            return null;
        }
        // Log the patcher file creation.
        $patcherFileName = $this->patcherFileName($name, $version);
        $this->addSuccess(
            \sprintf(
                'Created patcher file: %s with %d identifiers',
                $patcherFileName,
                \count($patcherContent)
            ),
        );


        try {
            $this->fileIO->write($patcherFileName, $patcherContent);
        } catch (\Throwable $th) {
            // Catch any exception and add to log.
            $this->addError(
                \sprintf(
                    'Error while writing patcher file: %s, with error %s on %s:%d',
                    $patcherFileName,
                    $th->getMessage(),
                    $th->getFile(),
                    $th->getLine()
                ),
            );

            return null;
        }
// dump($this->fileIO->getPath());
        return $this->fileIO->getPath() . \DIRECTORY_SEPARATOR . $patcherFileName;
    }

    /**
     * Creates a filename based on the given name and version
     *
     * @param string $name
     * @param string $version
     * @return string
     */
    public function patcherFileName(string $name, string $version): string
    {
        // Remove all whitespace from the name and version.
        $name = \preg_replace('/\s+/', '', $name);
        $version = \preg_replace('/\s+/', '', $version);

        // Create a custom filename, based on the name and version.
        return \sprintf('%s-%s.patcher', $name, $version);
    }

    /**
     * Get the log of the patcher creation
     *
     * @return array{mode: string, message:string}[]
     */
    public function getLog(): array
    {
        return $this->log;
    }

    /**
     * Add an error to the log
     *
     * @param string $message
     * @return self
     */
    public function addError(string $message): self
    {
        $this->log[] = [
            'mode' => 'error',
            'message' => $message
        ];

        return $this;
    }

    /**
     * Add a success to the log
     *
     * @param string $message
     * @return self
     */
    public function addSuccess(string $message): self
    {
        $this->log[] = [
            'mode' => 'success',
            'message' => $message
        ];

        return $this;
    }
}
