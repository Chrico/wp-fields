<?php

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Input;
use ChriCo\Fields\View\RenderableElementInterface;

class InputTest extends AbstractViewTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Input();
		$this->assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * Test rendering of an Element.
	 */
	public function test_render() {

		$element = new Element( 'foo' );
		$element->set_attribute( 'type', 'text' );

		$this->assertSame(
			'<input name="foo" type="text" />',
			( new Input() )->render( $element )
		);
	}
}