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

use Ustream\Option\None;
use Ustream\Option\Some;

/**
 * SomeTest
 */
class SomeTest extends PHPUnit_Framework_TestCase
{
	const SOME_VALUE = 'someValue';
	const SOME_OTHER_VALUE = 'someOtherValue';

	/**
	 * @test
	 * @return void
	 */
	public function someAlwaysExists()
	{
		$some = new Some(self::SOME_VALUE);
		$this->assertTrue($some->exists());
	}

	/**
	 * @test
	 * @return void
	 */
	public function someReturnsValue()
	{
		$value = 1;
		$some = new Some($value);
		$this->assertSame($value, $some->get());
	}

	public function getOrElseReturnsWrappedValue()
	{
		$some = new Some(self::SOME_VALUE);
		$otherValue = self::SOME_OTHER_VALUE;

		$this->assertSame(self::SOME_VALUE, $some->getOrElse($otherValue));
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrElseNeverExecutesCallback()
	{
		$testCase = $this;
		$some = new Some(self::SOME_VALUE);
		$some->getOrElse(function () use ($testCase) {
			$testCase->fail();
		});
	}

	/**
	 * @test
	 * @return void
	 */
	public function someAlwaysReturnValueNotThrowingException()
	{
		$some = new Some(self::SOME_VALUE);
		$this->assertSame(self::SOME_VALUE, $some->getOrThrow(new RuntimeException()));
	}

	/**
	 * @test
	 * @return void
	 */
	public function applyExecutesCallbackAndReturnsTransformatedOption()
	{
		$some = new Some(self::SOME_VALUE);

		$result = $some->apply(function ($value) {
			return new Some($value.'-transformated');
		});

		$this->assertEquals(new Some(self::SOME_VALUE . '-transformated'), $result);

		$this->assertEquals(new None(), $some->apply(function() { return new None(); }));
	}

	/**
	 * @test
	 * @return void
	 */
	public function applyExecutesCallbackAndReturnsWrappedValue()
	{
		$transformatedValue = self::SOME_VALUE . '-transformated';

		$some = new Some(self::SOME_VALUE);

		$result = $some->apply(function ($value) {
			return $value.'-transformated';
		});

		$this->assertEquals(new Some($transformatedValue), $result);
	}

	/**
	 * @test
	 * @return void
	 */
	public function applyReturnsNoneForVoid()
	{
		$some = new Some(self::SOME_VALUE);
		$this->assertEquals(new None(), $some->apply(function () {}));
	}

	/**
	 * @test
	 * @throws RuntimeException
	 * @return void
	 */
	public function someNeverExecuteOtherwiseButReturnsItSelf()
	{
		$testCase = $this;
		$some = new Some(self::SOME_VALUE);
		$this->assertSame($some, $some->otherwise(
			function () use ($testCase) {
				$testCase->fail();
			}
		));
	}

	/**
	 * @test
	 * @return void
	 */
	public function returnsNoneWhenPredicateIsFalse()
	{
		$some = new Some(10);
		$this->assertInstanceOf('Ustream\Option\None', $some->filter(function ($value) { return $value != 10; }));
	}

	/**
	 * @test
	 * @return void
	 */
	public function returnsItselfWhenPredicateIsTrue()
	{
		$some = new Some(10);
		$this->assertSame($some, $some->filter(function ($value) { return $value == 10; }));
	}
}

?>