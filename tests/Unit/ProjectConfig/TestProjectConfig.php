<?php

/**
 * Unit test for ProjectConfig.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package Gin0115/WP Scoper Core
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\Tests\Unit\ProjectConfig;

use PHPUnit\Framework\TestCase;
use Gin0115\WpScoper\ProjectConfig\ProjectConfig;

final class TestProjectConfig extends TestCase
{
    /**
     * Returns a populated ProjectConfig object.
     *
     * @return \Gin0115\WpScoper\ProjectConfig\ProjectConfig
     */
    public function fullConfigProvider(): ProjectConfig
    {
        return new ProjectConfig(
            'project-path',
            'vendor-path',
            'build-path',
            [
                [
                    'path' => 'source-path',
                    'files' => true,
                    'ignoreVcs' => true,
                    'notName' => 'not-name',
                    'exclude' => [
                        'exclude-path',
                    ],
                ],
            ],
            'namespace-prefix',
            'autoload-prefix',
            ['excluded-namespace'],
            ['excluded-stub'],
            ['excluded-symbol'],
            true
        );
    }

    /**
     * Returns a minimum ProjectConfig object.
     *
     * @return \Gin0115\WpScoper\ProjectConfig\ProjectConfig
     */
    public function minimumConfigProvider(): ProjectConfig
    {
        return new ProjectConfig(
            'project-path',
            'vendor-path',
            '',
            [
                [
                    'path' => 'source-path',
                    'files' => true,
                    'ignoreVcs' => true,
                    'notName' => 'not-name',
                    'exclude' => [
                        'exclude-path',
                    ],
                ],
            ],
            'namespace-prefix',
            'autoload-prefix',
            null,
            null,
            null,
            false
        );
    }

    /** @testdox It should be possible to get the project path from a config object */
    public function testGetProjectPath(): void
    {
        $this->assertEquals('project-path', $this->fullConfigProvider()->getProjectPath());
    }

    /** @testdox It should be possible to get the vendor path from a config object */
    public function testGetVendorPath(): void
    {
        $this->assertEquals('vendor-path', $this->fullConfigProvider()->getVendorPath());
    }

    /** @testdox It should be possible to get the build path from a config object */
    public function testGetBuildPath(): void
    {
        // From Full
        $this->assertEquals('build-path', $this->fullConfigProvider()->getBuildPath());

        // From Minimum
        $this->assertNull( $this->minimumConfigProvider()->getBuildPath());
    }

    /** @testdox It should be possible to get the source finders from a config object */
    public function testGetSourceFinders(): void
    {
        $this->assertEquals(
            [
                [
                    'path' => 'source-path',
                    'files' => true,
                    'ignoreVcs' => true,
                    'notName' => 'not-name',
                    'exclude' => [
                        'exclude-path',
                    ],
                ],
            ],
            $this->fullConfigProvider()->getSourceFinders()
        );
    }

    /** @testdox It should be possible to get the namespace prefix from a config object */
    public function testGetNamespacePrefix(): void
    {
        $this->assertEquals('namespace-prefix', $this->fullConfigProvider()->getNamespacePrefix());
    }

    /** @testdox It should be possible to get the autoload prefix from a config object */
    public function testGetAutoloadPrefix(): void
    {
        $this->assertEquals('autoload-prefix', $this->fullConfigProvider()->getAutoloadPrefix());
    }

    /** @testdox It should be possible to get the excluded namespaces from a config object */
    public function testGetExcludedNamespaces(): void
    {
        // From Full
        $this->assertEquals(
            ['excluded-namespace'],
            $this->fullConfigProvider()->getExcludedNamespaces()
        );

        // From Minimum
        $this->assertNull(
            $this->minimumConfigProvider()->getExcludedNamespaces()
        );
    }

    /** @testdox It should be possible to get the excluded stubs from a config object */
    public function testGetExcludedStubs(): void
    {
        // From Full
        $this->assertEquals(
            ['excluded-stub'],
            $this->fullConfigProvider()->getExcludedStubs()
        );

        // From Minimum
        $this->assertNull(
            $this->minimumConfigProvider()->getExcludedStubs()
        );
    }

    /** @testdox It should be possible to get the excluded symbols from a config object */
    public function testGetExcludedSymbols(): void
    {
        // From Full
        $this->assertEquals(
            ['excluded-symbol'],
            $this->fullConfigProvider()->getExcludedSymbols()
        );

        // From Minimum
        $this->assertNull(
            $this->minimumConfigProvider()->getExcludedSymbols()
        );
    }

    /** @testdox It should be possible to createa local cache for project */
    public function testGetAutoloader(): void
    {
        // From Full
        $this->assertTrue($this->fullConfigProvider()->getCreateLocalCache());

        // From Minimum
        $this->assertFalse($this->minimumConfigProvider()->getCreateLocalCache());
    }
}
