<?php

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Form;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class FormTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$expected_name = 'foo';
		$testee        = new Form( $expected_name );

		$this->assertInstanceOf( FormInterface::class, $testee );

		$this->assertSame( $expected_name, $testee->get_name() );

		$this->assertEmpty( $testee->get_value() );

		$this->assertCount( 0, $testee->get_errors() );
		$this->assertFalse( $testee->has_errors() );
		$this->assertCount( 0, $testee->get_label_attributes() );
	}

}