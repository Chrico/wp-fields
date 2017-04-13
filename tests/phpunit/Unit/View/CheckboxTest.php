<?php

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\View\Checkbox;
use ChriCo\Fields\View\RenderableElementInterface;

class CheckboxTest extends AbstractViewTestCase {

	/**
	 * Internal function to create a new ChoiceElement with type="checkbox".
	 *
	 * @param string              $name
	 * @param ChoiceListInterface $list
	 *
	 * @return ChoiceElement
	 */
	private function get_element( string $name, ChoiceListInterface $list ) {

		$element = new ChoiceElement( $name );
		$element->set_attribute( 'type', 'checkbox' );
		$element->set_choices( $list );

		return $element;
	}

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Checkbox();
		$this->assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * Test rendering of an CheckBox with empty ChoiceList.
	 */
	public function test_render__no_choices() {

		$element = $this->get_element( 'element', new ArrayChoiceList( [] ) );
		$this->assertSame( '', ( new Checkbox() )->render( $element ) );
	}

	/**
	 * Test rendering of an CheckBox with 1 item in ChoiceList.
	 */
	public function test_render__one_choice() {

		$element = $this->get_element( 'element', new ArrayChoiceList( [ 'foo' => 'bar' ] ) );

		$rendered = ( new Checkbox() )->render( $element );
		$this->assertContains( 'name="element"', $rendered );
		$this->assertContains( 'id="element"', $rendered );
		$this->assertContains( 'for="element"', $rendered );
		$this->assertContains( 'value="foo"', $rendered );
	}

	/**
	 * Test rendering of an CheckBox with 1 item in ChoiceList which is checked.
	 */
	public function test_render__one_choice_checked() {

		$expected_value = 'foo';
		$element        = $this->get_element( 'element', new ArrayChoiceList( [ $expected_value => 'bar' ] ) );
		$element->set_value( $expected_value );

		$this->assertContains( 'checked="checked"', ( new Checkbox() )->render( $element ) );
	}

	/**
	 * Test rendering of an CheckBox with multiple items in ChoiceList.
	 */
	public function test_render__multiple_choices() {

		$element = $this->get_element( 'element', new ArrayChoiceList( [ 'foo' => 'bar', 'baz' => 'bam' ] ) );

		$rendered = ( new Checkbox() )->render( $element );
		// both elements are having this name.
		$this->assertContains( 'name="element[]"', $rendered );

		// first element
		$this->assertContains( 'value="foo"', $rendered );
		$this->assertContains( 'id="element_foo"', $rendered );
		$this->assertContains( 'for="element_foo"', $rendered );

		// second element
		$this->assertContains( 'value="baz"', $rendered );
		$this->assertContains( 'id="element_baz"', $rendered );
		$this->assertContains( 'for="element_baz"', $rendered );
	}

}