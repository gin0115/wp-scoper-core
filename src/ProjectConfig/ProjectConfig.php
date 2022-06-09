<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\ProjectConfig;

class ProjectConfig
{
    /** @var string */
    private string $projectNamespace;

    /** @var string */
    private string $projectPath;

    /** @var string */
    private string $vendorDir;

    /** @var string */
    private string $namespacePrefix;

    /** @var string */
    private string $autoloadPrefix;

    /** @var string[] */
    private array $whiteList;

    /** @var array{package:string, path:string, destination:string}[] */
    private array $patchers;

    /** @var string[] */
    private array $customPatchers;

    /**
     * Create an instance of ProjectConfig.
     *
     * @param string $projectNamespace
     * @param string $projectPath
     * @param string $vendorDir
     * @param string $namespacePrefix
     * @param string $autoloadPrefix
     * @param string[] $whiteList
     * @param array{package:string, path:string, destination:string}[] $patchers
     * @param string[] $customPatchers
     */
    public function __construct(
        string $projectNamespace,
        string $projectPath,
        string $vendorDir,
        string $namespacePrefix,
        string $autoloadPrefix,
        array $whiteList,
        array $patchers,
        array $customPatchers
    ) {
        $this->projectNamespace = $projectNamespace;
        $this->projectPath = $projectPath;
        $this->vendorDir = $vendorDir;

        $this->namespacePrefix = $namespacePrefix;
        $this->autoloadPrefix = $autoloadPrefix;

        $this->whiteList = $whiteList;
        $this->patchers = $patchers;
        $this->customPatchers = $customPatchers;
    }

    /**
     * Get the value of projectNamespace
     * @return string
     */
    public function getProjectNamespace(): string
    {
        return $this->projectNamespace;
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
     * Get the value of vendorDir
     * @return string
     */
    public function getVendorDir(): string
    {
        return $this->vendorDir;
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
    public function getWhiteList(): array
    {
        return $this->whiteList;
    }

    /**
     * Get all patcher/stub definitions to be excluded from prefixing.
     *
     * @return array{package:string, path:string, destination:string}[]
     */
    public function getPatchers(): array
    {
        return $this->patchers;
    }

    /**
     * Get all custom function/class/constants that should be excluded from prefixing.
     *
     * @return string[]
     */
    public function getCustomPatchers()
    {
        return $this->customPatchers;
    }

    /**
     * Create a new config with a custom namespace.
     *
     * @param string $namespace
     * @return self
     */
    public function withNamespace(string $namespace): self
    {
        return new self(
            $namespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom project path.
     *
     * @param string $projectPath
     * @return self
     */
    public function withProjectPath(string $projectPath): self
    {
        return new self(
            $this->projectNamespace,
            $projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom vendor dir.
     *
     * @param string $vendorDir
     * @return self
     */
    public function withVendorDir(string $vendorDir): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom namespace prefix.
     *
     * @param string $namespacePrefix
     * @return self
     */
    public function withNamespacePrefix(string $namespacePrefix): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom autoload prefix.
     *
     * @param string $autoloadPrefix
     * @return self
     */
    public function withAutoloadPrefix(string $autoloadPrefix): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom whitelist.
     *
     * @param array $whiteList
     * @return self
     */
    public function withWhiteList(string $whiteList): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            array_merge([$whiteList], $this->whiteList),
            $this->patchers,
            $this->customPatchers
        );
    }


    /**
     * Create a new config with a custom patcher.
     *
     * @param array{package:string, path:string, destination:string} $patcher
     * @return self
     */
    public function withPatcher(array $patcher): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            array_merge($patcher, $this->patchers),
            $this->customPatchers
        );
    }

    /**
     * Create a new config with a custom custom patcher.
     *
     * @param string $customPatcher
     * @return self
     */
    public function withCustomPatcher(string $customPatcher): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            array_merge([$customPatcher], $this->customPatchers)
        );
    }

    /**
     * Create a new config with no whitelist.
     *
     * @return self
     */
    public function withoutWhiteList(): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            [],
            $this->patchers,
            $this->customPatchers
        );
    }

    /**
     * Create a new config with no patchers.
     *
     * @return self
     */
    public function withoutPatchers(): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            [],
            $this->customPatchers
        );
    }

    /**
     * Create a new config with no custom patchers.
     *
     * @return self
     */
    public function withoutCustomPatchers(): self
    {
        return new self(
            $this->projectNamespace,
            $this->projectPath,
            $this->vendorDir,
            $this->namespacePrefix,
            $this->autoloadPrefix,
            $this->whiteList,
            $this->patchers,
            []
        );
    }
}
