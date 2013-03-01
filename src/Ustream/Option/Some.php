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
 * Some
 */
class Some extends Option
{
	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * @param mixed $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * @return bool
	 */
	public function exists()
	{
		return true;
	}

	/**
	 * @throws RuntimeException
	 * @return mixed
	 */
	public function get()
	{
		return $this->value;
	}

	/**
	 * @param mixed $alternative
	 * @return mixed
	 */
	public function getOrElse($alternative)
	{
		return $this->value;
	}

	/**
	 * @param Exception|string $exception
	 * @return mixed
	 */
	public function getOrThrow($exception)
	{
		return $this->value;
	}

	/**
	 * @param callback $callback
	 * @return Option
	 */
	public function apply($callback)
	{
		$result = call_user_func($callback, $this->value);
		if ($result === null) {
			return new None();
		}
		return ($result instanceof Option) ? $result : new Some($result);
	}

	/**
	 * @param callback $callback
	 * @return Option
	 */
	public function otherwise($callback)
	{
		return $this;
	}

	/**
	 * @param callable $predicate
	 *
	 * @return Option
	 */
	public function filter($predicate)
	{
		return $predicate($this->value) ? $this : None::create();
	}
}

?>