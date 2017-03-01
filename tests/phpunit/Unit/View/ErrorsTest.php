<?php

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Errors;
use ChriCo\Fields\View\RenderableElementInterface;

class ErrorsTest extends AbstractViewTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Errors();
		$this->assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * Test rendering of an Element.
	 *
	 * @param array  $input
	 * @param string $expected
	 *
	 * @dataProvider provide_render
	 */
	public function test_render( $input, $expected ) {

		$stub = $this->getMockBuilder( Element::class )
			->setConstructorArgs( [ 'name' ] )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_errors' )
			->willReturn( $input );

		$this->assertSame(
			$expected,
			( new Errors() )->render( $stub )
		);
	}

	public function provide_render() {

		return [
			'no errors' => [
				[],
				''
			],
			'1 error'   => [
				[ 'foo' => 'bar' ],
				sprintf(
					Errors::WRAPPER_MARKUP,
					sprintf( Errors::ERROR_MARKUP, 'bar' )
				)
			]
		];
	}

	/**
	 * Test if we can change the markup around the error output.
	 */
	public function test_render__changed_markup() {

		$stub = $this->getMockBuilder( Element::class )
			->setConstructorArgs( [ 'name' ] )
			->getMock();
		$stub->expects( $this->once() )
			->method( 'get_errors' )
			->willReturn( [ 'foo' => 'bar' ] );

		$markup = [
			'wrapper' => '<ul>%s</ul>',
			'error'   => '<li>%s</li>'
		];

		$this->assertSame(
			'<ul><li>bar</li></ul>',
			( new Errors( $markup ) )->render( $stub )
		);
	}
}