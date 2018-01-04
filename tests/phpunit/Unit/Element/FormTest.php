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
		static::assertFalse( $testee->is_submitted() );
	}

	public function test_is_valid() {

		$expected_key   = 'foo';
		$expected_value = 'bar';
		$expected_error = [ 'bam' ];

		$expected_key2   = 'bam';
		$expected_value2 = 'baz';

		$expected_key3 = 'baz';

		// element which has additionally a validator which fails.
		$element = Mockery::mock( ElementInterface::class . ',' . ErrorAwareInterface::class );
		$element->shouldReceive( 'set_value' )
			->once()
			->with( $expected_value );
		$element->shouldReceive( 'is_disabled' )
			->andReturn( FALSE );
		$element->shouldReceive( 'get_name' )
			->andReturn( $expected_key );
		$element->shouldReceive( 'get_value' )
			->andReturn( $expected_value );
		$element->shouldReceive( 'set_errors' )
			->once()
			->with( $expected_error );

		// element which has no validator assigned.
		$not_validated_element2 = $this->get_element_stub( $expected_key2 );
		$not_validated_element2->shouldReceive( 'get_value' )
			->andReturn( $expected_value2 );

		// element which is disabled shouldn be validated
		$disabled_element = $this->get_element_stub( $expected_key3, TRUE );

		$validator = Mockery::mock( ValidatorInterface::class );
		$validator->shouldReceive( 'is_valid' )
			->once()
			->with( $expected_value )
			->andReturn( FALSE );
		$validator->shouldReceive( 'get_error_messages' )
			->once()
			->andReturn( $expected_error );

		$testee = new Form( '' );
		$testee->add_elements( [ $element, $not_validated_element2, $disabled_element ] );
		$testee->add_validator( $expected_key, $validator );
		$testee->submit(
			[
				$expected_key              => $expected_value,
				$expected_key2             => $expected_value2,
				$expected_key3             => 'foo',
				'non existing element key' => 'foo'
			]
		);

		static::assertFalse( $testee->is_valid() );
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\LogicException
	 */
	public function test_is_valid__not_submitted() {

		$testee = new Form( '' );
		$testee->is_valid();
	}

	public function test_set_data() {

		$expected_key   = 'foo';
		$expected_value = 'bar';
		$expected_key2  = 'baz';

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'set_value' )
			->with( Mockery::type( 'string' ) );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $expected_key );
		$element->shouldReceive( 'get_value' )
			->once()
			->andReturn( $expected_value );
		$element->shouldReceive( 'is_disabled' )
			->andReturn( FALSE );

		$element2 = $this->get_element_stub( $expected_key2, TRUE );

		$testee = new Form( '' );
		$testee->add_element( $element );
		$testee->add_element( $element2 );

		static::assertNull(
			$testee->set_data(
				[
					$expected_key   => $expected_value,
					$expected_key2  => 'foo',
					'undefined key' => 'foo'
				]
			)
		);

		static::assertFalse( $testee->is_submitted() );

		static::assertSame(
			$expected_value,
			$testee->get_element( $expected_key )
				->get_value()
		);
	}

	/**
	 * @expectedException \ChriCo\Fields\Exception\LogicException
	 */
	public function test_set_data__already_submitted() {

		$testee = new Form( '' );
		$testee->submit();
		$testee->set_data( [ 'foo' => 'bar' ] );
	}

	public function test_submit() {

		$expected_key   = 'foo';
		$expected_value = 'bar';

		$element = $this->get_element_stub( $expected_key );
		$element->shouldReceive( 'get_value' )
			->andReturn( $expected_value );

		$filter = Mockery::mock( FilterInterface::class );
		$filter->shouldReceive( 'filter' )
			->once()
			->with( $expected_value )
			->andReturn( $expected_value );

		$testee = new Form( '' );
		$testee->add_element( $element );
		$testee->add_filter( $expected_key, $filter );

		static::assertFalse( $testee->is_submitted() );
		static::assertNull( $testee->submit( [ $expected_key => $expected_value ] ) );
		static::assertTrue( $testee->is_submitted() );
	}

	public function test_set_attribute() {

		$expected_key   = 'foo';
		$expected_value = 'bar';
		$testee         = new Form( '' );

		// this writes directly to the form-element attributes.
		static::assertNull( $testee->set_attribute( $expected_key, $expected_value ) );
		static::assertSame( $expected_value, $testee->get_attribute( $expected_key ) );

		// this triggers a Form::bind_data
		static::assertNull( $testee->set_attribute( 'value', [ 'baz' => 'bam' ] ) );

	}

	public function test_add_filter_validator() {

		$expected_key = 'foo';

		$element = $this->get_element_stub( $expected_key );

		$testee = new Form( '' );
		$testee->add_element( $element );

		static::assertNull( $testee->add_filter( $expected_key, Mockery::mock( FilterInterface::class ) ) );
		static::assertNull( $testee->add_validator( $expected_key, Mockery::mock( ValidatorInterface::class ) ) );
	}

	private function get_element_stub( string $name, $disabled = FALSE ) {

		$element = Mockery::mock( ElementInterface::class );
		$element->shouldReceive( 'set_value' )
			->with( Mockery::type( 'string' ) );
		$element->shouldReceive( 'get_name' )
			->once()
			->andReturn( $name );
		$element->shouldReceive( 'is_disabled' )
			->andReturn( $disabled );

		return $element;
	}

}
