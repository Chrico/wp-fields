<?php

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
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

		$stub = $this->getMockBuilder( Element::class )
			->setConstructorArgs( [ 'name' ] )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_label_attributes' )
			->willReturn( $expected_attr );
		$stub->expects( $this->once() )
			->method( 'get_label' )
			->willReturn( $expected_label );

		$this->assertSame(
			'<label for="foo">Foo</label>',
			( new Label() )->render( $stub )
		);
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\InvalidClassException
	 */
	public function test_render__invalid_element() {

		$stub = $this->getMockBuilder( ElementInterface::class )
			->getMock();

		( new Label() )->render( $stub );
	}

	/**
	 * Test if the "for"-attribute is automatically used from element id if not set.
	 */
	public function test_render__for_attribute() {

		$expected_name  = 'name';
		$expected_label = 'Foo';

		$stub = $this->getMockBuilder( Element::class )
			->setConstructorArgs( [ $expected_name ] )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_id' )
			->willReturn( $expected_name );
		$stub->expects( $this->once() )
			->method( 'get_label_attributes' )
			->willReturn( [] );
		$stub->expects( $this->once() )
			->method( 'get_label' )
			->willReturn( $expected_label );

		$this->assertSame(
			'<label for="' . $expected_name . '">Foo</label>',
			( new Label() )->render( $stub )
		);
	}
}