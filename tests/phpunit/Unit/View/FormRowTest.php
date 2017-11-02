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
		static::assertContains( '<td colspan="2">', $output );
		static::assertContains( '</tr>', $output );
	}

	/**
	 * Test rendering of an Element with label
	 */
	public function test_render__with_label() {

		$expected_label = 'bar';

		$element = new Element( 'foo' );
		$element->set_attribute( 'type', 'text' );
		$element->set_label( $expected_label );

		$output = ( new FormRow() )->render( $element );
		static::assertContains( '<tr', $output );
		static::assertContains( 'class="form-row"', $output );
		static::assertContains( '<th><label', $output );
		static::assertContains( $expected_label . '</label></th>', $output );
		static::assertContains( '</tr>', $output );
	}
}