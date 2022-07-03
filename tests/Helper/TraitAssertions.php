<?php

/**
 * Trait for adding custom assertions to PHPUnit.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @package Gin0115/WP Scoper Core
 * @since 0.1.0
 */

declare(strict_types=1);

namespace Gin0115\WpScoper\Tests\Helper;

use function PinkCrab\FunctionConstructors\Arrays\filterCount;
use function PinkCrab\FunctionConstructors\Objects\isInstanceOf;

trait TraitAssertions
{
    /**
     * Assert is all instances in an array are of the same class as defined.
     *
     * @param object[]     $array     The array fo objects to check.
     * @param class-string $class     The class name to check for.
     * @param string|null  $message   Optional message to use if fails.
     * @return null
     */
    public function assertIsInstanceOfAll(array $instances, string $class, ?string $message = null): void
    {
        $closure = filterCount(isInstanceOf($class));
        $count = $closure($instances);

        $this->assertSame(
            count($instances),
            $count,
            $message ?? sprintf("Only %d of %d instances are of type %s", $count, count($instances), $class)
        );
    }
}
