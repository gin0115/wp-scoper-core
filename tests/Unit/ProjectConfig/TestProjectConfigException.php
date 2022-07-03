<?php

/**
 * Unit test for ProjectConfigException.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package Gin0115/WP Scoper Core
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\Tests\Unit\ProjectConfig;

use PHPUnit\Framework\TestCase;
use Gin0115\WpScoper\ProjectConfig\ProjectConfigException;

final class TestProjectConfigException extends TestCase
{
    /** @testdox It should be possible to create an exception for a missing config file, will the defined path populated as part of the message. */
    public function testMissingConfigFile(): void
    {
        $this->expectException(ProjectConfigException::class);
        $this->expectExceptionMessage('Missing config file in project path: project-path');
        throw ProjectConfigException::missingConfigFile('project-path');
    }

    /** @testdox It should be possible to create an exception for an invalid config file, will the defined message populated as part of the message. */
    public function testInvalidConfigFile(): void
    {
        $this->expectException(ProjectConfigException::class);
        $this->expectExceptionMessage('Invalid config: invalid config message');
        throw ProjectConfigException::invalidConfigFile('invalid config message');
    }
}
