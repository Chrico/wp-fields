<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\Form;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ValidatorInterface;
use Mockery;

class FormTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$expected_name = 'foo';
		$testee        = new Form( $expected_name );

		static::assertInstanceOf( FormInterface::class, $testee );

		static::assertSame( $expected_name, $testee->get_name() );

		static::assertEmpty( $testee->get_value() );
		static::assertEmpty( $testee->get_data() );

		static::assertCount( 0, $testee->get_errors() );
		static::assertFalse( $testee->has_errors() );
		static::assertTrue( $testee->is_valid() );
	}

	public function test_is_valid() {

		$expected_key   = 'foo';
		$expected_value = 'bar';
		$expected_error = [ 'bam' ];

		$expected_key2 = 'bam';

		// element which has additionally a validator which fails.
		$element = Mockery::mock( ElementInterface::class . ',' . ErrorAwareInterface::class );
		$element->shouldReceive( 'set_value' )
			->once()
			->with( $expected_value );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key );
		$element->shouldReceive( 'set_errors' )
			->once()
			->with( $expected_error );

		// element which has no validator assigned.
		$element2 = Mockery::mock( ElementInterface::class );
		$element2->shouldReceive( 'set_value' )
			->once()
			->with( Mockery::type( 'string' ) );
		$element2->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key2 );

		$validator = Mockery::mock( ValidatorInterface::class );
		$validator->shouldReceive( 'is_valid' )
			->once()
			->with( $expected_value )
			->andReturn( FALSE );
		$validator->shouldReceive( 'get_error_messages' )
			->once()
			->andReturn( $expected_error );

		$testee = new Form( '' );
		$testee->add_elements( [ $element, $element2 ] );
		$testee->add_validator( $expected_key, $validator );
		$testee->bind_data( [ $expected_key => $expected_value, $expected_key2 => '' ] );

		static::assertFalse( $testee->is_valid() );

		// call it twice will not re-validate everything.
		static::assertFalse( $testee->is_valid() );
	}

	public function test_set_data() {

		$expected_key   = 'foo';
		$expected_value = 'bar';

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'set_value' )
			->once()
			->with( Mockery::type( 'string' ) );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key );

		$testee = new Form( '' );
		$testee->add_element( $element );

		static::assertNull( $testee->set_data( [ $expected_key => $expected_value ] ) );
	}

	public function test_bind_data() {

		$expected_key   = 'foo';
		$expected_value = 'bar';

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'set_value' )
			->once()
			->with( Mockery::type( 'string' ) );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key );

		$filter = Mockery::mock( FilterInterface::class );
		$filter->shouldReceive( 'filter' )
			->once()
			->with( $expected_value )
			->andReturn( $expected_value );

		$testee = new Form( '' );
		$testee->add_element( $element );
		$testee->add_filter( $expected_key, $filter );

		static::assertNull( $testee->bind_data( [ $expected_key => $expected_value ] ) );
	}

	public function test_add_filter_validator() {

		$expected_key = 'foo';

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key );

		$testee = new Form( '' );
		$testee->add_element( $element );

		static::assertNull( $testee->add_filter( $expected_key, Mockery::mock( FilterInterface::class ) ) );
		static::assertNull( $testee->add_validator( $expected_key, Mockery::mock( ValidatorInterface::class ) ) );
	}

}