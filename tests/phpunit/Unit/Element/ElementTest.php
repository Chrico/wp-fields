<?php

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ElementTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavoir of the class.
	 */
	public function test_basic() {

		$expected_name = 'foo';
		$testee        = new Element( $expected_name );

		$this->assertInstanceOf( ElementInterface::class, $testee );
		$this->assertInstanceOf( LabelAwareInterface::class, $testee );

		$this->assertSame( $expected_name, $testee->get_name() );

		$this->assertEmpty( $testee->get_value() );
		$this->assertEmpty( $testee->get_label() );
		$this->assertEmpty( $testee->get_type() );

		$this->assertCount( 1, $testee->get_attributes() );
		$this->assertsame( [ 'name' => $expected_name ], $testee->get_attributes() );

		$this->assertCount( 0, $testee->get_errors() );
		$this->assertCount( 0, $testee->get_label_attributes() );
	}

	/**
	 * Test if we can set and get again the label.
	 */
	public function test_set_get_label() {

		$expected = 'test';

		$testee = new Element( 'id' );
		$testee->set_label( $expected );

		$this->assertSame( $expected, $testee->get_label() );
	}

	/**
	 * Test if we can set and get again the label attributes.
	 */
	public function test_set_get_label_attributes() {

		$expected = [ 'foo' => 'bar' ];

		$testee = new Element( 'id' );
		$testee->set_label_attributes( $expected );

		$this->assertSame( $expected, $testee->get_label_attributes() );
	}

	/**
	 * Test if we can set and get again the value.
	 */
	public function test_set_get_value() {

		$expected = 'test';

		$testee = new Element( 'id' );
		$testee->set_value( $expected );

		$this->assertSame( $expected, $testee->get_value() );
	}

	/**
	 * Test if we can set and get again the errors.
	 */
	public function test_set_get_errors() {

		$expected = [ 'foo' => 'bar', 'baz' => 'bam' ];

		$testee = new Element( 'id' );
		$testee->set_errors( $expected );

		$this->assertSame( $expected, $testee->get_errors() );
	}

	public function test_set_get_attributes() {

		$expected = [ 'name' => 'text', 'type' => 'bam' ];

		$testee = new Element( 'id' );
		$testee->set_attributes( $expected );

		$this->assertSame( $expected, $testee->get_attributes() );
	}

	/**
	 * Basic test to check, if we can set and get a single attribute.
	 */
	public function set_get_attribute() {

		$testee = new Element( 'id' );
		$testee->set_attribute( 'foo', 'bar' );

		$this->assertSame( 'bar', $testee->get_attribute( 'foo' ) );
		$this->assertSame( [ 'foo' => 'bar' ], $testee->get_attributes() );
	}

	public function test_set_get_options() {

		$expected = [ 'name' => 'text', 'type' => 'bam' ];

		$testee = new Element( 'id' );
		$testee->set_options( $expected );

		$this->assertSame( $expected, $testee->get_options() );
	}

	/**
	 * Basic test to check, if we can set and get a single option.
	 */
	public function set_get_option() {

		$testee = new Element( 'id' );
		$testee->set_option( 'foo', 'bar' );

		$this->assertSame( 'bar', $testee->get_option( 'foo' ) );
		$this->assertSame( [ 'foo' => 'bar' ], $testee->get_options() );
	}

}