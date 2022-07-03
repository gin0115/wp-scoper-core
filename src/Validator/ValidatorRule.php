<?php

/**
 * Validator Rule
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

namespace Gin0115\WpScoper\Validator;

use Closure;

class ValidatorRule
{
    /**
     * @var callable(mixed):bool
     */
    private $predicate;

    private string $message;

    public function __construct(callable $predicate, string $message)
    {
        $this->message = $message;
        $this->predicate = $predicate;
    }

    /**
     * Get the message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the predicate
     *
     * @return callable(mixed):bool
     */
    public function getConditional(): callable
    {
        return $this->predicate;
    }
}
