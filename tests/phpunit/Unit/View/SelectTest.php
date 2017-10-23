<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\RenderableElementInterface;
use ChriCo\Fields\View\Select;

class SelectTest extends AbstractViewTestCase {

	/**
	 * Internal function to create a new ChoiceElement with type="select".
	 *
	 * @param string              $name
	 * @param ChoiceListInterface $list
	 *
	 * @return ChoiceElement
	 */
	private function get_element( string $name, ChoiceListInterface $list ) {

		$element = new ChoiceElement( $name );
		$element->set_attribute( 'type', 'select' );
		$element->set_choices( $list );

		return $element;
	}

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Select();
		static::assertInstanceOf( RenderableElementInterface::class, $testee );
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\InvalidClassException
	 */
	public function test_render__invalid_element() {

		( new Select() )->render(
			$this->getMockBuilder( ElementInterface::class )
				->getMock()
		);
	}

	/**
	 * Test rendering of an Select with empty ChoiceList.
	 */
	public function test_render__no_choices() {

		$element  = $this->get_element( 'element', new ArrayChoiceList( [] ) );
		$rendered = ( new Select() )->render( $element );

		static::assertContains( '<select', $rendered );
		static::assertContains( 'name="element"', $rendered );
		static::assertContains( 'id="element"', $rendered );
		static::assertContains( '</select>', $rendered );
	}

	/**
	 * Test rendering of an Select with 1 item in ChoiceList.
	 */
	public function test_render__one_choice() {

		$element = $this->get_element( 'element', new ArrayChoiceList( [ 'foo' => 'bar' ] ) );

		$rendered = ( new Select() )->render( $element );

		static::assertContains( '<select', $rendered );
		static::assertContains( 'name="element"', $rendered );

		static::assertContains( '<option', $rendered );
		static::assertContains( 'value="foo"', $rendered );
		static::assertContains( 'bar</option>', $rendered );

		static::assertContains( '</select>', $rendered );
	}

	/**
	 * Test rendering of an Select with 1 item in ChoiceList which is selected.
	 */
	public function test_render__one_choice_selected() {

		$expected_value = 'foo';
		$element        = $this->get_element( 'element', new ArrayChoiceList( [ $expected_value => 'bar' ] ) );
		$element->set_value( $expected_value );

		static::assertContains( 'selected="selected"', ( new Select() )->render( $element ) );
	}

	/**
	 * Test rendering of an Select with multiple items in ChoiceList.
	 */
	public function test_render__multiple_choices() {

		$element = $this->get_element( 'element', new ArrayChoiceList( [ 'foo' => 'bar', 'baz' => 'bam' ] ) );

		$rendered = ( new Select() )->render( $element );
		static::assertContains( '<select', $rendered );
		static::assertContains( 'name="element"', $rendered );

		// First element
		static::assertContains( '<option', $rendered );
		static::assertContains( 'value="foo"', $rendered );
		static::assertContains( 'bar</option>', $rendered );

		// second element
		static::assertContains( '<option', $rendered );
		static::assertContains( 'value="baz"', $rendered );
		static::assertContains( 'bam</option>', $rendered );

		static::assertContains( '</select>', $rendered );
	}

}