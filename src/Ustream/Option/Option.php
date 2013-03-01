<?php
/**
 * This file is part of the Option package.
 *
 * @copyright Ustream Inc.
 * @author pepov <pepov@ustream.tv>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ustream\Option;

use Exception;

/**
 * Option
 */
abstract class Option
{
	/**
	 * @param mixed $value
	 * @param mixed $noneValue
	 *
	 * @return None|Some
	 */
	public static function createFromValue($value, $noneValue = null)
	{
		return $value === $noneValue ? None::create() : new Some($value);
	}

	/**
	 * @return bool
	 */
	abstract public function exists();

	/**
	 * @abstract
	 * @throws RuntimeException
	 * @return mixed
	 */
	abstract public function get();

	/**
	 * @param mixed $alternative
	 * @return mixed
	 */
	abstract public function getOrElse($alternative);

	/**
	 * @abstract
	 * @param Exception|string $exception The string primitive will be converted to new Exception(string)
	 * @throws Exception
	 * @return mixed
	 */
	abstract public function getOrThrow($exception);

	/**
	 * @abstract
	 * @param callable $callback
	 * @return Option
	 */
	abstract public function apply($callback);

	/**
	 * @abstract
	 * @param callable $callback
	 * @return Option
	 */
	abstract public function otherwise($callback);

	/**
	 * @param callable $predicate
	 * @return Option
	 */
	abstract public function filter($predicate);
}

?>