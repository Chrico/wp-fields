<?php # -*- coding: utf-8 -*-

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

		static::assertInstanceOf( ElementInterface::class, $testee );
		static::assertInstanceOf( LabelAwareInterface::class, $testee );

		static::assertSame( $expected_name, $testee->get_id() );
		static::assertSame( $expected_name, $testee->get_name() );
		static::assertEmpty( $testee->get_type() );
		static::assertEmpty( $testee->get_value() );

		static::assertEmpty( $testee->get_label() );
		static::assertCount( 0, $testee->get_label_attributes() );

		static::assertCount( 2, $testee->get_attributes() );
		static::assertSame( [ 'name' => $expected_name, 'id' => $expected_name ], $testee->get_attributes() );

		static::assertCount( 0, $testee->get_options() );

		static::assertCount( 0, $testee->get_errors() );
		static::assertFalse( $testee->has_errors() );

		static::assertFalse( $testee->is_disabled() );
	}

	/**
	 * Test if we can set and get again the description.
	 */
	public function test_set_get_description() {

		$expected = 'test';

		$testee = new Element( 'id' );
		$testee->set_description( $expected );

		static::assertSame( $expected, $testee->get_description() );
	}

	/**
	 * Test if we can set and get again the label.
	 */
	public function test_set_get_label() {

		$expected = 'test';

		$testee = new Element( 'id' );
		$testee->set_label( $expected );

		static::assertSame( $expected, $testee->get_label() );
	}

	/**
	 * Test if we can set and get again the label attributes.
	 */
	public function test_set_get_label_attributes() {

		$expected = [ 'foo' => 'bar' ];

		$testee = new Element( 'id' );
		$testee->set_label_attributes( $expected );

		static::assertSame( $expected, $testee->get_label_attributes() );
	}

	/**
	 * Test if we can set and get again the value.
	 */
	public function test_set_get_value() {

		$expected = 'test';

		$testee = new Element( 'id' );
		$testee->set_value( $expected );

		static::assertSame( $expected, $testee->get_value() );
	}

	/**
	 * Test if we can set and get again the errors.
	 */
	public function test_set_get_errors() {

		$expected = [ 'foo' => 'bar', 'baz' => 'bam' ];

		$testee = new Element( 'id' );
		$testee->set_errors( $expected );

		static::assertSame( $expected, $testee->get_errors() );
		static::assertTrue( $testee->has_errors() );
	}

	public function test_set_get_attributes() {

		$expected_id = 'id';
		$expected    = [ 'name' => 'text', 'type' => 'bam' ];

		$testee = new Element( $expected_id );
		$testee->set_attributes( $expected );

		$attributes = $testee->get_attributes();

		static::assertArrayHasKey( 'name', $attributes );
		static::assertArrayHasKey( 'type', $attributes );
		static::assertArrayHasKey( 'id', $attributes );
	}

	public function test_set_get_options() {

		$expected = [ 'name' => 'text', 'type' => 'bam' ];

		$testee = new Element( 'id' );
		$testee->set_options( $expected );

		static::assertSame( $expected, $testee->get_options() );
	}

	/**
	 * Basic test to check, if we can set and get a single option.
	 */
	public function test_set_get_option() {

		$testee = new Element( 'id' );
		$testee->set_option( 'foo', 'bar' );

		static::assertSame( 'bar', $testee->get_option( 'foo' ) );
		static::assertSame( [ 'foo' => 'bar' ], $testee->get_options() );
		static::assertSame( '', $testee->get_option( 'undefined key' ) );
	}

	public function test_is_disabled() {

		$testee = new Element( '' );
		$testee->set_attribute( 'disabled', TRUE );

		static::assertTrue( $testee->is_disabled() );
	}
}