<?php

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Tests\Unit\Fixtures;
use ChriCo\Fields\View\Label;
use ChriCo\Fields\View\RenderableElementInterface;

class LabelTest extends AbstractViewTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Label();
		$this->assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * Test rendering of an Element.
	 *
	 */
	public function test_render() {

		$expected_attr  = [ 'for' => 'foo' ];
		$expected_label = 'Foo';

		$element = new Element( 'name' );
		$element->set_label( $expected_label );
		$element->set_label_attributes( $expected_attr );

		$this->assertSame(
			'<label for="foo">Foo</label>',
			( new Label() )->render( $element )
		);
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\InvalidClassException
	 */
	public function test_render__invalid_element() {

		( new Label() )->render( new Fixtures\ElementWithoutLabelAwareInterface() );
	}

	/**
	 * Test if the "for"-attribute is automatically used from element id if not set.
	 */
	public function test_render__for_attribute() {

		$expected_name  = 'name';
		$expected_label = 'Foo';

		$element = new Element( $expected_name );
		$element->set_label( $expected_label );

		$this->assertSame(
			'<label for="' . $expected_name . '">Foo</label>',
			( new Label() )->render( $element )
		);
	}
}