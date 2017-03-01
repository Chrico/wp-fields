<?php

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ChoiceElementTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new ChoiceElement( 'name' );
		$list   = $testee->get_choices();
		$this->assertInstanceOf( ArrayChoiceList::class, $list );
		$this->assertEmpty( $list->get_choices() );
	}

	/**
	 * Test set and get choices methods.
	 */
	public function test_set_get_choices() {

		$expected_values = [ 'foo' => 'bar' ];

		$stub = $this->getMockBuilder( ChoiceListInterface::class )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_choices' )
			->willReturn( $expected_values );

		$testee = new ChoiceElement( 'name' );
		$testee->set_choices( $stub );

		$list = $testee->get_choices();
		$this->assertSame( $stub, $list );
		$this->assertSame( $expected_values, $list->get_choices() );
	}

}