<?php
namespace ChriCo\Fields\Tests\Unit;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\Element\ElementInterface;

class ElementFactoryTest extends AbstractTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new ElementFactory();
		$this->assertInstanceOf( ElementFactory::class, $testee );
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
		$this->assertInstanceOf( $expected, $testee->create( $spec ) );
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
			'missing name' => [
				[ 'attributes' => [ 'type' => 'text' ] ]
			],
			'missing type' => [
				[ 'attributes' => [ 'name' => 'foo' ] ]
			],
			'both missing' => [
				[ 'label' => 'foo' ]
			],
			'empty specs'  => [
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

		( new ElementFactory() )->create( [ 'attributes' => [ 'type' => 'foo', 'name' => '' ] ] );
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

		$this->assertSame( $expected_label, $element->get_label() );

		// order in array is not the same.
		$this->assertEquals( $expected_label_attributes, $element->get_label_attributes() );
	}

	/**
	 * Test creation of a ChoiceElement.
	 */
	public function test_create__with_choices() {

		$testee = new ElementFactory();

		$spec = [
			'attributes' => [
				'type' => 'select',
				'name' => 'test',
			],
			'choices'    => [
				'foo' => 'bar',
				'baz' => 'bam'
			]
		];

		$element = $testee->create( $spec );

		$choices = $element->get_choices();
		$this->assertInstanceOf( ChoiceListInterface::class, $choices );
		$this->assertNotEmpty( $choices->get_choices() );
	}

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
		$this->assertInstanceOf( CollectionElementInterface::class, $element );

		$elements = $element->get_elements();
		$this->assertNotEmpty( $elements );
		$this->assertInstanceOf( ElementInterface::class, reset( $elements ) );
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
			'data-bar' => 'bar'
		];

		$element = $testee->create( [ 'attributes' => $expected ] );
		// cannot use assertSame() because the order of the elements in array will differ.
		$this->assertEquals( $expected, $element->get_attributes() );
	}

	/**
	 * Test if we can create multiple Elements from a specification.
	 */
	public function test_create_multiple() {

		// Element
		$element = [
			'attributes' => [
				'type' => 'text',
				'name' => 'my-text'
			],
			'label' => 'My label'
		];
		// ChoiceElement
		$choice = [
			'attributes' => [
				'type' => 'select',
				'name' => 'my-select'
			]
		];
		// Collection - with additional elements
		$collection = [
			'attributes' => [
				'type' => 'collection',
				'name' => 'my-collection'
			],
			'elements'   => [ $element, $choice ]
		];

		$elements = ( new ElementFactory() )->create_multiple( [ $element, $choice, $collection ] );

		$this->assertCount( 3, $elements );
		$this->assertInstanceOf( ElementInterface::class, $elements[ 0 ] );
		$this->assertInstanceOf( ChoiceElementInterface::class, $elements[ 1 ] );
		$this->assertInstanceOf( CollectionElementInterface::class, $elements[ 2 ] );
	}
}