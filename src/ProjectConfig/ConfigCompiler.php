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

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
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
     * Update config file with the given config.
     *
     * @param ProjectConfig $config
     * @return void
     */
    public function updateConfig(ProjectConfig $config): void
    {
        $yaml = $this->createYamlFromConfig($config);
        file_put_contents($this->getConfigFilePath(), $yaml);
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
            $config['project_namespace'] ?? '',
            $config['project_path'] ?? '',
            $config['vendor_dir'] ?? '',
            $config['namespace_prefix'] ?? '',
            $config['autoload_prefix'] ?? '',
            $config['white_list'] ?? [],
            $config['patchers'] ?? [],
            $config['custom_patchers'] ?? []
        );
    }

    /**
     * Create YAML config from existing ProjectConfig.
     *
     * @param ProjectConfig $config
     * @return string
     */
    private function createYamlFromConfig(ProjectConfig $config): string
    {
        $yaml = [
            'project_namespace' => $config->getProjectNamespace(),
            'project_path' => $config->getProjectPath(),
            'vendor_dir' => $config->getVendorDir(),
            'namespace_prefix' => $config->getNamespacePrefix(),
            'autoload_prefix' => $config->getAutoloadPrefix(),
            'white_list' => $config->getWhiteList(),
            'patchers' => $config->getPatchers(),
            'custom_patchers' => $config->getCustomPatchers(),
        ];

        return Yaml::dump($yaml);
    }
}
