<?php # -*- coding: utf-8 -*-

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

		static::assertInstanceOf( FormInterface::class, $testee );

		static::assertSame( $expected_name, $testee->get_name() );

		static::assertEmpty( $testee->get_value() );
		static::assertEmpty( $testee->get_data() );

		static::assertCount( 0, $testee->get_errors() );
		static::assertFalse( $testee->has_errors() );
	}

}