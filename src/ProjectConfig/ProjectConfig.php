<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\ProjectConfig;

class ProjectConfig
{
    /** @var string */
    private string $projectPath;

    /** @var string */
    private string $vendorPath;

    /** @var string */
    private string $buildPath;

    /** @array{path:string, files:true, ignoreVcs:bool, notName: string, exclude: string[]}[] */
    private array $sourceFinders;

    /** @var string[] */
    private array $sourceFiles;

    /** @var string */
    private string $namespacePrefix;

    /** @var string */
    private string $autoloadPrefix;

    /** @var string[] */
    private array $excludedNamespaces;

    /** @var string[] */
    private array $excludedStubs;

    /** @var string[] */
    private array $excludedSymbols;

    /**
     *
     * @param string $projectPath
     * @param string $vendorPath
     * @param string $vendorPath
     * @param string[] $sourceFinders
     * @param string[] $sourceFiles
     * @param string $namespacePrefix
     * @param string $autoloadPrefix
     * @param string[] $excludedNamespaces
     * @param string[] $excludedStubs
     * @param string[] $excludedSymbols
     */
    public function __construct(
        string $projectPath,
        string $vendorPath,
        string $buildPath,
        array $sourceFinders,
        array $sourceFiles,
        string $namespacePrefix,
        string $autoloadPrefix,
        array $excludedNamespaces,
        array $excludedStubs,
        array $excludedSymbols
    ) {
        $this->projectPath = $projectPath;
        $this->vendorPath = $vendorPath;
        $this->buildPath = $buildPath;

        $this->sourceFinders = $sourceFinders;
        $this->sourceFiles = $sourceFiles;


        $this->namespacePrefix = $namespacePrefix;
        $this->autoloadPrefix = $autoloadPrefix;

        $this->excludedNamespaces = $excludedNamespaces;
        $this->excludedStubs = $excludedStubs;
        $this->excludedSymbols = $excludedSymbols;
    }


    /**
     * Get the value of projectPath
     * @return string
     */
    public function getProjectPath(): string
    {
        return $this->projectPath;
    }

    /**
     * Get the value of vendorPath
     * @return string
     */
    public function getVendorPath(): string
    {
        return $this->vendorPath;
    }


    /**
     * Get the value of buildPath
     * @return string
     */
    public function getBuildPath(): string
    {
        return $this->buildPath;
    }

    /**
     * Get the value of namespacePrefix
     * @return string
     */
    public function getNamespacePrefix(): string
    {
        return $this->namespacePrefix;
    }

    /**
     * Get the custom autoload prefix for this project.
     * @return string
     */
    public function getAutoloadPrefix(): string
    {
        return $this->autoloadPrefix;
    }

    /**
     * Get all namespaces which should be excluded from prefixing.
     * @return string[]
     */
    public function getExcludedNamespaces(): array
    {
        return $this->excludedNamespaces;
    }

    /**
     * Get all patcher/stub definitions to be excluded from prefixing.
     *
     * @return string[]
     */
    public function getExcludedStubs(): array
    {
        return $this->excludedStubs;
    }

    /**
     * Get all custom function/class/constants that should be excluded from prefixing.
     *
     * @return string[]
     */
    public function getExcludedSymbols(): array
    {
        return $this->excludedSymbols;
    }


    /**
     * Get the value of sourceFinders
     *
     * @return string[]
     */
    public function getSourceFinders(): array
    {
        return $this->sourceFinders;
    }

     /**
      * Get the value of sourceFiles
      *
      * @return string[]
      */
    public function getSourceFiles(): array
    {
         return $this->sourceFiles;
    }
}
