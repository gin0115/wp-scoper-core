<?php

/**
 * Validates a project config
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

use Closure;
use Gin0115\WpScoper\Validator\Validator;
use Gin0115\WpScoper\Helper\ValidatorTuple;
use Gin0115\WpScoper\Validator\ValidatorRule;

use function PinkCrab\FunctionConstructors\Arrays\filterAll;
use function PinkCrab\FunctionConstructors\Strings\containsPattern;
use function PinkCrab\FunctionConstructors\GeneralFunctions\compose as 🔧;
use function PinkCrab\FunctionConstructors\Comparisons\isGreaterThanOrEqualTo;

class ProjectConfigValidator implements Validator
{
    /**
     * All properties that must be set in the config file.
     *
     * @var string[]
     */
    private array $requiredProperties = [
        'projectPath',
        'sourceFinder',
        'namespacePrefix',
        'autoloadPrefix',
    ];

    /**
     * All properties that are optional in the config file.
     *
     * @var string[]
     */
    private array $optionalProperties = [
        'buildPath',
        'vendorPath',
        'excludedNamespaces',
        'excludedStubs',
        'excludedSymbols',
        'createLocalCache',
    ];

    /**
     * Returns a conditional to check if the property is required.
     *
     * @return \Closure
     */
    private function isRequired(): Closure
    {
        return function (string $property): bool {
            return in_array($property, $this->requiredProperties);
        };
    }

    /**
     * Returns a conditional to check if the property is optional.
     *
     * @return ValidatorRule[]
     */
    private function isNoneBlankString($field): array
    {
        return [
                new ValidatorRule('is_string', "{$field} must be a string."),
                new ValidatorRule(🔧('mb_strlen', isGreaterThanOrEqualTo(1)), "{$field} must not be blank"),
            ];
    }

    /**
     * Validates a project config.
     *
     * @param mixed $config
     * @return bool
     */
    public function validate($config): bool
    {
    }

    /**
     * Validates the project path
     *
     * @return ValidatorTuple[]
     */
    public function projectPath(): array
    {
        return [
            ...$this->isNoneBlankString('Project Path'),
            new ValidatorRule('is_dir', 'Project path must be a directory.'),
        ];
    }

    /**
     * Validates the vendor path
     *
     * @return ValidatorTuple[]
     */
    public function vendorPath(): array
    {
        return [
            ...$this->isNoneBlankString('Vendor path'),
            new ValidatorRule('is_dir', 'Vendor path must be a directory.'),
        ];
    }

    /**
     * Validates the build path
     *
     * @return ValidatorTuple[]
     */
    public function buildPath(): array
    {
        return $this->isNoneBlankString('Build Path');
    }
    /**
     * Validates the namespace prefix
     *
     * @return ValidatorTuple[]
     */
    public function namespacePrefix(): array
    {
        return [
            ...$this->isNoneBlankString('Namespace Prefix'),
            new ValidatorRule(containsPattern('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/'), 'Namespace prefix must be a valid string.'),
        ];
    }

    /**
     * Validates the autoload prefix
     *
     * @return ValidatorTuple[]
     */
    public function autoloadPrefix(): array
    {
        return [
            ...$this->isNoneBlankString('Autoload Prefix'),
            new ValidatorRule(containsPattern('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/'), 'Autoload prefix must be a valid string.'),
        ];
    }

    /**
     * Validates the excluded namespaces
     *
     * @return ValidatorTuple[]
     */
    public function excludedNamespaces(): array
    {
        return [
            new ValidatorRule('is_array', 'Excluded namespaces must be an array.'),
            new ValidatorRule(filterAll('is_string'), 'Excluded namespaces must be an array of strings.'),
            new ValidatorRule(filterAll(containsPattern('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/')), 'Excluded namespaces must be an array of valid namespaces.'),
        ];
    }
}
