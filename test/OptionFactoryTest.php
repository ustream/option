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

use Ustream\Option\Option;

/**
 * FactoryTest
 */
class OptionFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @return void
	 */
	public function createSomeFromValue()
	{
		$this->assertInstanceOf('Ustream\Option\Some', Option::createFromValue('asd'));
		$this->assertSame('asd', Option::createFromValue('asd')->get());
	}

	/**
	 * @test
	 * @return void
	 */
	public function createNoneFromNull()
	{
		$this->assertInstanceOf('Ustream\Option\None', Option::createFromValue(null));
	}

	/**
	 * @test
	 * @return void
	 */
	public function createNoneFromValue()
	{
		$value = 'asd';
		$valueTreatedAsNone = $value;
		$this->assertInstanceOf('Ustream\Option\None', Option::createFromValue($value, $valueTreatedAsNone));
	}
}

?>