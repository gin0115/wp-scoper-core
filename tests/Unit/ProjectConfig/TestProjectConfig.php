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
            ['excluded-symbol']
        );
    }

    /** @testdox It should be possible to get the project path from a config object */
    public function testGetProjectPath()
    {
        $this->assertEquals('project-path', $this->configProvider()->getProjectPath());
    }

    /** @testdox It should be possible to get the vendor path from a config object */
    public function testGetVendorPath()
    {
        $this->assertEquals('vendor-path', $this->configProvider()->getVendorPath());
    }

    /** @testdox It should be possible to get the build path from a config object */
    public function testGetBuildPath()
    {
        $this->assertEquals('build-path', $this->configProvider()->getBuildPath());
    }

    /** @testdox It should be possible to get the source finders from a config object */
    public function testGetSourceFinders()
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
    public function testGetSourceFiles()
    {
        $this->assertEquals(
            ['source-file-path'],
            $this->configProvider()->getSourceFiles()
        );
    }

    /** @testdox It should be possible to get the namespace prefix from a config object */
    public function testGetNamespacePrefix()
    {
        $this->assertEquals('namespace-prefix', $this->configProvider()->getNamespacePrefix());
    }

    /** @testdox It should be possible to get the autoload prefix from a config object */
    public function testGetAutoloadPrefix()
    {
        $this->assertEquals('autoload-prefix', $this->configProvider()->getAutoloadPrefix());
    }

    /** @testdox It should be possible to get the excluded namespaces from a config object */
    public function testGetExcludedNamespaces()
    {
        $this->assertEquals(
            ['excluded-namespace'],
            $this->configProvider()->getExcludedNamespaces()
        );
    }

    /** @testdox It should be possible to get the excluded stubs from a config object */
    public function testGetExcludedStubs()
    {
        $this->assertEquals(
            ['excluded-stub'],
            $this->configProvider()->getExcludedStubs()
        );
    }

    /** @testdox It should be possible to get the excluded symbols from a config object */
    public function testGetExcludedSymbols()
    {
        $this->assertEquals(
            ['excluded-symbol'],
            $this->configProvider()->getExcludedSymbols()
        );
    }
}
