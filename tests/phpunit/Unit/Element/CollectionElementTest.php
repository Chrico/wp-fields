<?php

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\BaseElement;
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class CollectionElementTest extends AbstractTestCase {

	public function test_basic() {

		$expected_name = 'name';
		$testee        = new CollectionElement( $expected_name );

		$this->assertInstanceOf( BaseElement::class, $testee );
		$this->assertInstanceOf( ElementInterface::class, $testee );

		$this->assertEmpty( $testee->get_elements() );
	}

	/**
	 * Basic test to add 1 element and get the element back.
	 */
	public function test_add_get_elements() {

		$element_name = 'element-name';

		$element = new Element( $element_name );

		$testee = new CollectionElement( 'collection-name' );
		$testee->add_element( $element );

		$elements = $testee->get_elements();

		$this->assertCount( 1, $elements );
		$this->assertArrayHasKey( $element_name, $elements );
		$this->assertSame( $element, $elements[ $element_name ] );
		$this->assertSame( $element, $testee->get_element( $element_name ) );
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\ElementNotFoundException
	 */
	public function test_get_element__not_exist() {

		( new CollectionElement( 'collection' ) )->get_element( 'not existing element' );
	}

	/**
	 * Test if success/failure is returned for not existing/existing elements.
	 */
	public function test_has_element() {

		$expected_element_name = 'element';
		$testee                = new CollectionElement( 'collection' );

		$this->assertFalse( $testee->has_element( $expected_element_name ) );

		$testee->add_element( new Element( $expected_element_name ) );
		$this->assertTrue( $testee->has_element( $expected_element_name ) );
	}

	/**
	 * Test if errors which are not matching with the element name are assigned to the collection itself.
	 */
	public function test_add_errors() {

		$expected_error = [ 'error_message' ];

		$testee = new CollectionElement( 'collection' );
		$testee->add_element( new Element( 'element' ) );
		$testee->set_errors( $expected_error );

		$this->assertSame( $expected_error, $testee->get_errors() );
	}

	/**
	 * Test if errors are delegated to the elements in the collection.
	 */
	public function test_add_errors__delegate() {

		$expected_element_name = 'element';
		$expected_error        = [ $expected_element_name => 'error_message' ];

		$testee = new CollectionElement( 'collection' );
		$testee->add_element( new Element( $expected_element_name ) );
		$testee->set_errors( $expected_error );

		/** @var ErrorAwareInterface $element */
		$element = $testee->get_element( $expected_element_name );

		$this->assertSame( $expected_error, $element->get_errors() );
	}

	/**
	 * Test if values are delegated to the elements in the collection.
	 */
	public function test_set_attribute__value() {

		$expected_element_name = 'element';
		$expected_value        = 'the value';

		$testee = new CollectionElement( 'collection' );
		$testee->add_element( new Element( $expected_element_name ) );
		$testee->set_value( [ $expected_element_name => $expected_value ] );

		$element = $testee->get_element( $expected_element_name );

		$this->assertSame( $expected_value, $element->get_value() );
	}

	/**
	 * Test if we can get all values back again after delegation on set_value().
	 */
	public function test_get_attribute__value() {

		$expected_element_name = 'element';
		$expected_value        = 'the value';
		$expected              = [ $expected_element_name => $expected_value ];

		$testee = new CollectionElement( 'collection' );
		$testee->add_element( new Element( $expected_element_name ) );
		$testee->set_value( $expected );

		$this->assertSame( $expected, $testee->get_value() );
	}
}