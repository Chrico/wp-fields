<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\FormRow;
use ChriCo\Fields\View\RenderableElementInterface;

class FormRowTest extends AbstractViewTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new FormRow();
		static::assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * Test rendering of an Element.
	 */
	public function test_render() {

		$element = new Element( 'foo' );
		$element->set_attribute( 'type', 'text' );

		$output = ( new FormRow() )->render( $element );
		static::assertContains( '<tr', $output );
		static::assertContains( 'class="form-row"', $output );
		static::assertContains( '</tr>', $output );
	}
}