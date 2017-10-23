<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\CallbackChoiceList;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ElementFactory;

class ElementFactoryTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new ElementFactory();
		static::assertInstanceOf( ElementFactory::class, $testee );
	}

	/**
	 * Test if creating a View-class via valid type.
	 *
	 * @param $spec
	 * @param $expected
	 *
	 * @dataProvider provide_create
	 */
	public function test_create( $spec, $expected ) {

		$testee = new ElementFactory();
		static::assertInstanceOf( $expected, $testee->create( $spec ) );
	}

	/**
	 * @return array
	 */
	public function provide_create() {

		return [
			'element'    => [
				[
					'attributes' => [
						'type' => 'text',
						'name' => 'foo',
					]
				],
				ElementInterface::class
			],
			'choice'     => [
				[
					'attributes' => [
						'type' => 'checkbox',
						'name' => 'foo'
					]
				],
				ChoiceElementInterface::class
			],
			'collection' => [
				[
					'attributes' => [
						'type' => 'collection',
						'name' => 'foo'
					]
				],
				CollectionElementInterface::class
			]
		];
	}

	/**
	 * @param array $spec
	 *
	 * @dataProvider provide_create__missing_attributes
	 *
	 * @expectedException \ChriCo\Fields\Exception\MissingAttributeException
	 */
	public function test_create__missing_attributes( $spec ) {

		( new ElementFactory() )->create( $spec );
	}

	public function provide_create__missing_attributes() {

		return [
			'missing name'          => [
				[ 'attributes' => [ 'type' => 'text' ] ]
			],
			'missing type'          => [
				[ 'attributes' => [ 'name' => 'foo' ] ]
			],
			'type and name missing' => [
				[ 'label' => 'foo' ]
			],
			'empty specs'           => [
				[]
			]
		];
	}

	/**
	 * Test if the creation of an invalid class fails.
	 *
	 * @expectedException \ChriCo\Fields\Exception\InvalidClassException
	 */
	public function test_create__invalid_class() {

		( new ElementFactory() )->create( [ 'attributes' => [ 'type' => ElementFactory::class, 'name' => '' ] ] );
	}

	/**
	 * Test if the creation with an unknown type fails.
	 *
	 * @expectedException \ChriCo\Fields\Exception\UnknownTypeException
	 */
	public function test_create__unknown_type() {

		( new ElementFactory() )->create( [ 'attributes' => [ 'type' => 'i am an unknown type', 'name' => '' ] ] );
	}

	/**
	 * Test if we can create a Text-Element which implements the LabelAwareInterface and set label and label_attributes.
	 */
	public function test_create__with_label() {

		$testee                    = new ElementFactory();
		$expected_label            = 'my label';
		$expected_label_attributes = [ 'class' => 'foo', 'for' => 'bar' ];

		$spec = [
			'attributes'       => [
				'type' => 'text',
				'name' => 'test',
			],
			'label'            => $expected_label,
			'label_attributes' => $expected_label_attributes
		];

		$element = $testee->create( $spec );

		static::assertSame( $expected_label, $element->get_label() );

		// order in array is not the same.
		static::assertEquals( $expected_label_attributes, $element->get_label_attributes() );
	}

	/**
	 * Test if we can create a Text-Element which implements the DescriptionAwareInterface and set description
	 */
	public function test_create__with_description() {

		$testee   = new ElementFactory();
		$expected = 'lorum ipsum';

		$spec = [
			'attributes'  => [
				'type' => 'text',
				'name' => 'test',
			],
			'description' => $expected
		];

		$element = $testee->create( $spec );

		static::assertSame( $expected, $element->get_description() );
	}

	/**
	 * Test if we can create a Text-Element which implements the ErrorAwareInterface and set errors
	 */
	public function test_create__with_errors() {

		$testee   = new ElementFactory();
		$expected = [ 'foo' => 'bar' ];

		$spec = [
			'attributes' => [
				'type' => 'text',
				'name' => 'test',
			],
			'errors'     => $expected
		];

		$element = $testee->create( $spec );

		static::assertSame( $expected, $element->get_errors() );
	}

	/**
	 * Test creation of a ChoiceElement.
	 *
	 * @dataProvider provide_create__with_choices
	 *
	 * @param array|callable $choices
	 * @param array          $expected
	 * @param string         $instance_of
	 *
	 */
	public function test_create__with_choices( $choices, $expected, $instance_of ) {

		$testee = new ElementFactory();

		$spec = [
			'attributes' => [
				'type' => 'select',
				'name' => 'test',
			],
			'choices'    => $choices
		];

		$element = $testee->create( $spec );

		$choices = $element->get_choices();
		static::assertInstanceOf( $instance_of, $choices );
		static::assertSame( $expected, $choices->get_choices() );
	}

	public function provide_create__with_choices() {

		$data = [];

		// normal choice list
		$data [ 'choice array' ] = [
			[ 'foo' => 'bar', 'baz' => 'bam' ],
			[ 'foo' => 'bar', 'baz' => 'bam' ],
			ArrayChoiceList::class
		];

		// choice list with callback
		$expected                       = [ 'foo' => 'bar', 'baz' => 'bam' ];
		$data[ 'choices via callable' ] = [
			function () use ( $expected ) {

				return $expected;
			},
			$expected,
			CallbackChoiceList::class
		];

		return $data;
	}

	/**
	 * Test creation of an Collection element with elements.
	 */
	public function test_create__with_collection() {

		$testee = new ElementFactory();

		$spec = [
			'attributes' => [
				'type' => 'collection',
				'name' => 'test',
			],
			'elements'   => [
				[
					'attributes' => [
						'type' => 'text',
						'name' => 'my-text'
					]
				]
			]
		];

		$element = $testee->create( $spec );
		static::assertInstanceOf( CollectionElementInterface::class, $element );

		$elements = $element->get_elements();
		static::assertNotEmpty( $elements );
		static::assertInstanceOf( ElementInterface::class, reset( $elements ) );
	}

	/**
	 * Test creating of an element with additional attributes.
	 */
	public function test_create__with_attributes() {

		$testee = new ElementFactory();

		$expected = [
			'type'     => 'text',
			'name'     => 'test',
			'class'    => 'class-1 class-2',
			'data-foo' => 'foo',
			'data-bar' => 'bar',
			'id'       => 'test'
		];

		$element = $testee->create( [ 'attributes' => $expected ] );
		// cannot use assertSame() because the order of the elements in array can differ.
		static::assertEquals( $expected, $element->get_attributes() );
	}

	/**
	 * Test if we can create multiple Elements from a specification.
	 */
	public function test_create__multiple() {

		// Element
		$element = [
			'attributes' => [
				'type' => 'text',
				'name' => 'my-text',
				'id'   => 'my-id'
			],
			'label'      => 'My label'
		];
		// ChoiceElement
		$choice = [
			'attributes' => [
				'type' => 'select',
				'name' => 'my-select',
				'id'   => 'my-id'
			]
		];
		// Collection - with additional elements
		$collection = [
			'attributes' => [
				'type' => 'collection',
				'name' => 'my-collection',
				'id'   => 'my-collection'
			],
			'elements'   => [ $element, $choice ]
		];

		$elements = ( new ElementFactory() )->create_multiple( [ $element, $choice, $collection ] );

		static::assertCount( 3, $elements );
		static::assertInstanceOf( ElementInterface::class, $elements[ 0 ] );
		static::assertInstanceOf( ChoiceElementInterface::class, $elements[ 1 ] );
		static::assertInstanceOf( CollectionElementInterface::class, $elements[ 2 ] );
	}
}