<?php # -*- coding: utf-8 -*-

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
		static::assertInstanceOf( RenderableElementInterface::class, $testee );
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

		$output = ( new Label() )->render( $element );
		static::assertContains( '<label', $output );
		static::assertContains( '</label>', $output );
		static::assertContains( 'for="foo"', $output );
		static::assertContains( '>Foo</label>', $output );
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\InvalidClassException
	 */
	public function test_render__invalid_element() {

		/** @var \Mockery\MockInterface|ElementInterface $stub */
		$stub = \Mockery::mock( ElementInterface::class );
		$stub->shouldReceive( 'get_name' )
			->andReturn( '' );

		( new Label() )->render( $stub );
	}

	/**
	 * Test if the "for"-attribute is automatically used from element id if not set.
	 */
	public function test_render__for_attribute() {

		$expected_name  = 'name';
		$expected_label = 'Foo';

		$element = new Element( $expected_name );
		$element->set_label( $expected_label );

		$output = ( new Label() )->render( $element );
		static::assertContains( 'for="' . $expected_name . '"', $output );
		static::assertContains( '>' . $expected_label . '</label>', $output );

	}
}