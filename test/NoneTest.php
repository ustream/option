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
 * NoneTest
 */
class NoneTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @return void
	 */
	public function noneIsAlwaysNoneExistent()
	{
		$none = new None();
		$this->assertFalse($none->exists());
	}

	/**
	 * @test
	 * @return void
	 */
	public function noneThrowsExceptionForGet()
	{
		$this->setExpectedException('RuntimeException');

		$none = new None();
		$none->get();
	}

	/**
	 * @test
	 * @return void
	 */
	public function noneAlwaysReturnsDefault()
	{
		$none = new None();
		$this->assertSame('default', $none->getOrElse('default'));
	}

	/**
	 * @test
	 * @return void
	 */
	public function noneAlwaysExecutesCallback()
	{
		$none = new None();
		$this->assertSame(1, $none->getOrElse(function () { return 1; }));
	}

	/**
	 * @test
	 * @throws RuntimeException
	 * @return void
	 */
	public function noneAlwaysThrowsException()
	{
		$this->setExpectedException('RuntimeException');

		$none = new None();
		$none->getOrThrow(new RuntimeException());
	}

	/**
	 * @test
	 * @return void
	 */
	public function throwsBaseExceptionForStringValue()
	{
		$this->setExpectedException('RuntimeException', 'asdasd');

		None::create()->getOrThrow('asdasd');
	}

	/**
	 * @test
	 * @return void
	 */
	public function throwsRuntimeExceptionForOtherThanStringOrExceptionType()
	{
		$this->setExpectedException('RuntimeException');

		None::create()->getOrThrow(0);
	}

	/**
	 * @test
	 * @return void
	 */
	public function neverAppliesJustReturnsNone()
	{
		$testCase = $this;
		$none = new None();
		$this->assertSame($none, $none->apply(
			function ($value) use ($testCase) {
				$testCase->fail();
			}
		));
	}

	/**
	 * @test
	 * @return void
	 */
	public function otherwiseExecutesCallbackAndReturnsOptionResult()
	{
		$none = new None();
		$callbackResult = new Some('some value');
		$result = $none->otherwise(function () use ($callbackResult) { return $callbackResult; });
		$this->assertSame($callbackResult, $result);
	}

	/**
	 * @test
	 * @return void
	 */
	public function otherwiseWrapsValueAsOptionResult()
	{
		$none = new None();
		$this->assertEquals(new Some(1), $none->otherwise(function () { return 1; }));
	}


	/**
	* @test
	* @return void
	*/
	public function otherwiseTranslatesNoneForVoid()
	{
		$none = new None();
		$this->assertInstanceOf('\Ustream\Option\None', $none->otherwise(function () {}));
	}

	/**
	 * @test
	 * @return void
	 */
	public function filterNoMatterAlwaysReturnsNone()
	{
		$none = new None();
		$this->assertSame($none, $none->filter(function () { return true; }));
		$this->assertSame($none, $none->filter(function () { return false; }));
	}
}

?>