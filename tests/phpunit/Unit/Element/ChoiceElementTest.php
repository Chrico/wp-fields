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

		$expected = new ArrayChoiceList();
		$testee   = new ChoiceElement( 'name' );
		$testee->set_choices( $expected );

		$this->assertSame( $expected, $testee->get_choices() );
	}

}