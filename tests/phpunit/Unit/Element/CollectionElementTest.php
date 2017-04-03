<?php
namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class CollectionElementTest extends AbstractTestCase {

	public function test_basic() {

		$expected_name = 'name';
		$testee        = new CollectionElement( $expected_name );

		$this->assertInstanceOf( Element::class, $testee );
		$this->assertInstanceOf( ElementInterface::class, $testee );

		$this->assertEmpty( $testee->get_elements() );
	}

	/**
	 * Basic test to add 1 element and get the element back.
	 *
	 * @param string $element_name
	 * @param string $element_id
	 * @param string $collection_name
	 * @param string $expected_element_id
	 *
	 * @dataProvider provide_add_get_elements
	 */
	public function test_add_get_elements( $element_name, $element_id, $collection_name, $expected_element_id ) {

		$element = new Element( $element_name );
		$element->set_attribute( 'id', $element_id );

		$testee = new CollectionElement( $collection_name );
		$testee->add_element( $element );

		$elements = $testee->get_elements();

		$this->assertCount( 1, $elements );
		$this->assertArrayHasKey( $expected_element_id, $elements );
		$this->assertSame( $element, $elements[ $expected_element_id ] );
	}

	public function provide_add_get_elements() {

		return [
			'get_id isset'    => [
				'element-name',
				'element-id',
				'collection-name',
				"element-id"
			],
			'get_id is empty' => [
				'element-name',
				'',
				'collection-name',
				"element-name"
			]
		];
	}
}