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
use RuntimeException;

/**
 * None
 */
class None extends Option
{
	/**
	 * @static
	 * @return None
	 */
	public static function create()
	{
		/**
		 * @var None
		 */
		static $instance;
		if ($instance === null) {
			$instance = new None();
		}
		return $instance;
	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return false;
	}

	/**
	 * @throws RuntimeException
	 * @return mixed
	 */
	public function get()
	{
		throw new RuntimeException('Requested value does not exist');
	}

	/**
	 * @param mixed $alternative
	 * @return mixed
	 */
	public function getOrElse($alternative)
	{
		if (is_callable($alternative)) {
			return call_user_func($alternative);
		} else {
			return $alternative;
		}
	}

	/**
	 * @param Exception|string $exception
	 * @throws Exception
	 * @return mixed
	 */
	public function getOrThrow($exception)
	{
		if (is_string($exception)) {
			throw new RuntimeException($exception);
		} elseif ($exception instanceof Exception) {
			throw $exception;
		} else {
			throw new RuntimeException('Unknown exception type (must be a primitive string or Exception)');
		}
	}

	/**
	 * @param callback $callback
	 * @return Option
	 */
	public function apply($callback)
	{
		return $this;
	}

	/**
	 * @param callback $callback
	 * @return Option
	 */
	public function otherwise($callback)
	{
		$result = call_user_func($callback);
		if ($result === null) {
			return new None();
		}
		return ($result instanceof Option) ? $result : new Some($result);
	}

	/**
	 * @param callable $predicate
	 *
	 * @return Option
	 */
	public function filter($predicate)
	{
		return $this;
	}
}

?>