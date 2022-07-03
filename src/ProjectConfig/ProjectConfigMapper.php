<?php

/**
 * Maps to and from ProjectConfig objects.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package Gin0115/WP Scoper Core
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\ProjectConfig;

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Parser;

/**
 * @template SourceFinder as array<string, mixed[]>
 * @template ConfigArray as array{
 *    projectPath: string,
 *    vendorPath: string,
 *    buildPath: string,
 *    sourceFinder: <SourceFinder>,
 *    sourceFiles: string[],
 *    namespacePrefix: string,
 *    autoloadPrefix: string,
 *    excludedNamespaces: ?string[],
 *    excludedStubs: ?string[][],
 *    excludedSymbols: ?string[],
 *    createLocalCache: bool
 * }
 */
class ProjectConfigMapper
{
    private Parser $yamlParser;
    private Filesystem $filesystem;

    public function __construct(Parser $yamlParser, Filesystem $filesystem)
    {
        $this->yamlParser = $yamlParser;
        $this->filesystem = $filesystem;
    }

    /**
     * Creates a projectConfig object from a config (yaml) file.
     *
     * @param string $path
     * @return ProjectConfig
     */
    public function fromYamlFile(string $path): ProjectConfig
    {
        if (!$this->filesystem->has($path)) {
            throw ProjectConfigException::missingConfigFile($path);
        }
        $yaml = $this->filesystem->read($path);
        $config = $this->yamlParser->parse($yaml);
        return $this->fromArray($config);
    }

    /**
     * Creates a projectConfig object from an array.
     *
     * @param <ConfigArray> $config
     * @return ProjectConfig
     */
    public function fromArray(array $config): ProjectConfig
    {
        return new ProjectConfig(
            $config['projectPath'] ?? '',
            $config['vendorPath'] ?? '',
            $config['buildPath'] ?? '',
            $config['sourceFinder'] ?? [],
            $config['namespacePrefix'] ?? '',
            $config['autoloadPrefix'] ?? '',
            $config['excludedNamespaces'] ?? null,
            $config['excludedStubs'] ?? null,
            $config['excludedSymbols'] ?? null,
            $config['createLocalCache'] ?? true
        );
    }
}
