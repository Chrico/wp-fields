<?php

namespace ChriCo\Fields\Tests\Unit\View;

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

		$expected = [
			'name' => 'foo',
			'type' => 'text'
		];

		$stub = $this->getMockBuilder( ElementInterface::class )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_attributes' )
			->willReturn( $expected );

		$this->assertSame(
			'<input name="foo" type="text" />',
			( new Input() )->render( $stub )
		);
	}
}