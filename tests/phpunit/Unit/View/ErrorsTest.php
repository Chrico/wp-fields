<?php declare( strict_types=1 );

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
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

		$element = new Element( 'name' );
		$element->set_errors( $input );

		$this->assertSame(
			$expected,
			( new Errors() )->render( $element )
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

		$element = new Element( 'name' );
		$element->set_errors( [ 'foo' => 'bar' ] );

		$markup = [
			'wrapper' => '<ul>%s</ul>',
			'error'   => '<li>%s</li>'
		];

		$this->assertSame(
			'<ul><li>bar</li></ul>',
			( new Errors( $markup ) )->render( $element )
		);
	}
}