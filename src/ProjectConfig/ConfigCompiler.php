<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\ProjectConfig;

use Symfony\Component\Yaml\Yaml;

class ConfigCompiler
{
    /**
     * The filename of the config file.
     */
    private const CONFIG_FILE_NAME = 'wp-scoper.yml';

    /**
     * Base path of the project
     *
     * @var string
     */
    private $projectPath;

    /**
     * Global path where this is installed
     *
     * @var string
     */
    private $globalPath;

    public function __construct(string $projectPath, string $globalPath)
    {
        $this->projectPath = $projectPath;
        $this->globalPath = $globalPath;
    }

    /**
     * Get the config file path.
     *
     * @return string
     */
    public function getConfigFilePath(): string
    {
        return $this->projectPath . DIRECTORY_SEPARATOR . self::CONFIG_FILE_NAME;
    }

    /**
     * Checks if the config file exists for this current project.
     *
     * @return bool
     */
    public function hasConfig(): bool
    {
        return file_exists($this->getConfigFilePath());
    }

    /**
     * Gets the config for the current project.
     *
     * @return array
     */
    public function getConfig(): ProjectConfig
    {
        // Check we have a valid config file.
        if (!$this->hasConfig()) {
            throw ConfigException::missingConfigFile($this->projectPath);
        }

        try {
            return $this->createConfigFromYaml(
                $this->getConfigFilePath()
            );
        } catch (\Throwable $th) {
            throw ConfigException::invalidConfigFile($th->getMessage());
        }
    }

    /**
     * Create an instance of ProjectConfig from an existing config file.
     *
     * @param string $yamlPath
     * @return ProjectConfig
     */
    private function createConfigFromYaml(string $yamlPath): ProjectConfig
    {
        $config = Yaml::parseFile($yamlPath);

        return new ProjectConfig(
            $config['project_path'] ?? '',
            $config['vendor_dir'] ?? '',
            $config['build_dir'] ?? '',
            $config['source_paths'] ?? [],
            $config['source_files'] ?? [],
            $config['namespace_prefix'] ?? '',
            $config['autoload_prefix'] ?? '',
            $config['ignored_namespaces'] ?? [],
            $this->mapPathPlaceholders($config['patcher_stubs'] ?? []),
            $config['patcher_symbols'] ?? []
        );
    }

    /**
     * Maps an array of paths, replaces placeholders with the project or global path.
     *
     * @param array $paths
     * @return array
     */
    private function mapPathPlaceholders(array $paths): array
    {
        return array_map(function ($path) {
            return str_replace(
                ['{project_path}', '{global_path}'],
                [$this->projectPath, $this->globalPath],
                $path
            );
        }, $paths);
    }
}
