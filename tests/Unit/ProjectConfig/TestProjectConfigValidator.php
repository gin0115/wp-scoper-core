<?php

/**
 * Unit test for ProjectConfigValidator.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package Gin0115/WP Scoper Core
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\Tests\Unit\ProjectConfig;

use PHPUnit\Framework\TestCase;
use Gin0115\WpScoper\Helper\ValidatorTuple;
use Gin0115\WpScoper\Validator\ValidatorRule;
use PinkCrab\FunctionConstructors\Arrays as Arr;
use Gin0115\WpScoper\Tests\Helper\TraitAssertions;
use Gin0115\WpScoper\ProjectConfig\ProjectConfigValidator;

use function PinkCrab\FunctionConstructors\Comparisons\all;

final class TestProjectConfigValidator extends TestCase
{
    use TraitAssertions;


    private ProjectConfigValidator $validator;
    protected function setUp(): void
    {
        $this->validator = new ProjectConfigValidator();
    }

    /**
     * Runs through an array of ValidatorTuples and validates with the value passed.
     *
     * @param ValidatorTuples[] $validators
     * @param mixed $values
     * @return bool
     */
    public function runValidators(array $validators, $value): bool
    {
        // Iterate through all validators, if any fail return the message.
        foreach ($validators as $validator) {
            $callable = $validator->getConditional();
            $message = $validator->getMessage();

            if (!$callable($value)) {
                return false;
            }
        }

        return true;
    }

    /** @testdox It should be possible to validate a projectPath using the array of validators defined. */
    public function testValidateProjectPath(): void
    {
        $projectPathValidators = $this->validator->projectPath();

        // Check all are ValidatorRule objects.
        $this->assertIsInstanceOfAll($projectPathValidators, ValidatorRule::class);

        // Should fail if not a string.
        $this->assertFalse($this->runValidators($projectPathValidators, []));

        // Should fail if empty string.
        $this->assertFalse($this->runValidators($projectPathValidators, ''));

        // Should fail if not a directory.
        $this->assertFalse($this->runValidators($projectPathValidators, 'file.php'));

        // Should pass if a directory.
        $this->assertTrue($this->runValidators($projectPathValidators, __DIR__));
    }

    /** @testdox It should be possible to validate a vendorPath using the array of validators defined. */
    public function testValidateVendorPath(): void
    {
        $vendorPathValidators = $this->validator->vendorPath();

        // Check all are ValidatorRule objects.
        $this->assertIsInstanceOfAll($vendorPathValidators, ValidatorRule::class);

        // Should fail if not a string.
        $this->assertFalse($this->runValidators($vendorPathValidators, []));

        // Should fail if empty string.
        $this->assertFalse($this->runValidators($vendorPathValidators, ''));

        // Should fail if not a directory.
        $this->assertFalse($this->runValidators($vendorPathValidators, 'file.php'));

        // Should pass if a directory.
        $this->assertTrue($this->runValidators($vendorPathValidators, __DIR__));
    }


    // /** @testdox It should be possible to validate a sourceFinder using the array of validators defined. */
    // public function testValidateSourceFinder(): void
    // {
    //     $sourceFinderValidators = $this->validator->sourceFinder();

    //     // Check all are ValidatorRule objects.
    //     $this->assertIsInstanceOfAll($sourceFinderValidators, ValidatorRule::class);

    //     // Should fail if not a string.
    //     $this->assertFalse($this->runValidators($sourceFinderValidators, []));

    //     // Should fail if empty string.
    //     $this->assertFalse($this->runValidators($sourceFinderValidators, ''));

    //     // Should fail if not a file.
    //     $this->assertFalse($this->runValidators($sourceFinderValidators, 'directory'));

    //     // Should pass if a file.
    //     $this->assertTrue($this->runValidators($sourceFinderValidators, __FILE__));
    // }




    /** @testdox It should be possible to validate a buildPath using the array of validators defined.*/
    public function testBuildPathValidator(): void
    {
        $buildPathValidators = $this->validator->buildPath();

        // Check all are ValidatorRule objects.
        $this->assertIsInstanceOfAll($buildPathValidators, ValidatorRule::class);

        // value not a string, should fail.
        $this->assertFalse($this->runValidators($buildPathValidators, 1));

        // empty string, should fail.
        $this->assertFalse($this->runValidators($buildPathValidators, ''));

        // Valid string (not blank), should pass.
        $this->assertTrue($this->runValidators($buildPathValidators, __DIR__));
    }

    /** @testdox It should be possible to validate a namespacePrefix using the array of validators defined.*/
    public function testNamespacePrefixValidator(): void
    {
        $namespacePrefixValidators = $this->validator->namespacePrefix();

        // Check all are ValidatorRule objects.
        $this->assertIsInstanceOfAll($namespacePrefixValidators, ValidatorRule::class);

        // value not a string, should fail.
        $this->assertFalse($this->runValidators($namespacePrefixValidators, 1));

        // empty string, should fail.
        $this->assertFalse($this->runValidators($namespacePrefixValidators, ''));

        // Valid string (not blank), should pass.
        $this->assertTrue($this->runValidators($namespacePrefixValidators, 'Single'));
        $this->assertTrue($this->runValidators($namespacePrefixValidators, 'Dou\Ble'));

        // Invalid strings should fail.
        $this->assertFalse($this->runValidators($namespacePrefixValidators, '1'));
        $this->assertFalse($this->runValidators($namespacePrefixValidators, '_'));
        $this->assertFalse($this->runValidators($namespacePrefixValidators, '-'));
        $this->assertFalse($this->runValidators($namespacePrefixValidators, '.'));
        $this->assertFalse($this->runValidators($namespacePrefixValidators, '..'));
        $this->assertFalse($this->runValidators($namespacePrefixValidators, 'Closing\\'));

    }

    /** @testdox It should be possible to validate a excludedNamespace using the array of validators defined. */
    public function testExcludedNamespaceValidator(): void
    {
        $excludedNamespaceValidators = $this->validator->excludedNamespaces();

        // Check all are ValidatorRule objects.
        $this->assertIsInstanceOfAll($excludedNamespaceValidators, ValidatorRule::class);

        // value not an array, it should fail.
        $this->assertFalse($this->runValidators($excludedNamespaceValidators, 1));

        // Array with none strings, it should fail.
        $this->assertFalse($this->runValidators($excludedNamespaceValidators, [1, 2, 3]));

        // empty array, should pass.
        $this->assertTrue($this->runValidators($excludedNamespaceValidators, []));

        // Valid array with namespcaes, should pass.
        $this->assertTrue($this->runValidators($excludedNamespaceValidators, ['Gin0115\WpScoper\Tests\Unit']));

        // Array with invalid namespaces, should fail.
        $this->assertFalse($this->runValidators($excludedNamespaceValidators, ['Gin0115\WpScoper\Tests\Unit', '**Not valid']));
        $this->assertFalse($this->runValidators($excludedNamespaceValidators, ['Gin0115\WpScoper\Tests\Unit', '/wrong/way']));
        $this->assertFalse($this->runValidators($excludedNamespaceValidators, ['Gin0115\WpScoper\Tests\Unit', '\\NoLeading\\Foo']));
    }
}
