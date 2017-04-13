<?php

namespace ChriCo\Fields\Tests\Unit;

use ChriCo\Fields\Tests\Unit\Fixtures\CustomViewElement;
use ChriCo\Fields\View\Textarea;
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\View\Errors;
use ChriCo\Fields\View\Input;
use ChriCo\Fields\View\Label;

class ViewFactoryTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new ViewFactory();
		$this->assertInstanceOf( ViewFactory::class, $testee );
	}

	/**
	 * Test if creating a View-class via valid type.
	 *
	 * @param $type
	 * @param $expected
	 *
	 * @dataProvider provide_create
	 */
	public function test_create( $type, $expected ) {

		$testee = new ViewFactory();
		$this->assertInstanceOf( $expected, $testee->create( $type ) );
	}

	/**
	 * @return array
	 */
	public function provide_create() {

		return [
			'label type'  => [ 'label', Label::class ],
			'errors type' => [ 'errors', Errors::class ],
			'text type'   => [ 'text', Input::class ],
			'class name'  => [ Textarea::class, Textarea::class ]
		];
	}

	/**
	 * Test if we can create an instance of a custom view-element.
	 */
	public function test_create__custom_view_element() {

		$expected = CustomViewElement::class;
		$this->assertInstanceOf( $expected, ( new ViewFactory() )->create( $expected ) );
	}

	/**
	 * Test if an unknown type throws an Exception.
	 *
	 * @expectedException \ChriCo\Fields\Exception\UnknownTypeException
	 */
	public function test_create__unknown_type() {

		$testee = new ViewFactory();
		$testee->create( 'i am an unknown type' );
	}
}