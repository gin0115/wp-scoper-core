<?php

/**
 * Unit test for ProjectConfig.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GPL-3.0-or-later
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
    public function configProvider(): ProjectConfig
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
            ['source-file-path'],
            'namespace-prefix',
            'autoload-prefix',
            ['excluded-namespace'],
            ['excluded-stub'],
            ['excluded-symbol'],
            true
        );
    }

    /** @testdox It should be possible to get the project path from a config object */
    public function testGetProjectPath(): void
    {
        $this->assertEquals('project-path', $this->configProvider()->getProjectPath());
    }

    /** @testdox It should be possible to get the vendor path from a config object */
    public function testGetVendorPath(): void
    {
        $this->assertEquals('vendor-path', $this->configProvider()->getVendorPath());
    }

    /** @testdox It should be possible to get the build path from a config object */
    public function testGetBuildPath(): void
    {
        $this->assertEquals('build-path', $this->configProvider()->getBuildPath());
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
            $this->configProvider()->getSourceFinders()
        );
    }

    /** @testdox It should be possible to get the source files from a config object */
    public function testGetSourceFiles(): void
    {
        $this->assertEquals(
            ['source-file-path'],
            $this->configProvider()->getSourceFiles()
        );
    }

    /** @testdox It should be possible to get the namespace prefix from a config object */
    public function testGetNamespacePrefix(): void
    {
        $this->assertEquals('namespace-prefix', $this->configProvider()->getNamespacePrefix());
    }

    /** @testdox It should be possible to get the autoload prefix from a config object */
    public function testGetAutoloadPrefix(): void
    {
        $this->assertEquals('autoload-prefix', $this->configProvider()->getAutoloadPrefix());
    }

    /** @testdox It should be possible to get the excluded namespaces from a config object */
    public function testGetExcludedNamespaces(): void
    {
        $this->assertEquals(
            ['excluded-namespace'],
            $this->configProvider()->getExcludedNamespaces()
        );
    }

    /** @testdox It should be possible to get the excluded stubs from a config object */
    public function testGetExcludedStubs(): void
    {
        $this->assertEquals(
            ['excluded-stub'],
            $this->configProvider()->getExcludedStubs()
        );
    }

    /** @testdox It should be possible to get the excluded symbols from a config object */
    public function testGetExcludedSymbols(): void
    {
        $this->assertEquals(
            ['excluded-symbol'],
            $this->configProvider()->getExcludedSymbols()
        );
    }

    /** @testdox It should be possible to createa local cache for project */
    public function testGetAutoloader(): void
    {
        $this->assertTrue($this->configProvider()->getCreateLocalCache());
    }
}
