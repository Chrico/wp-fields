<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\View\AttributeFormatterTrait;

class AttributeFormatterTraitTest extends AbstractViewTestCase {

	/**
	 * @dataProvider provide_get_attributes_as_string
	 */
	public function test_get_attributes_as_string( array $input, string $expected ) {

		static::assertSame(
			$expected,
			/** @var AttributeFormatterTrait $testee */
			$this->getMockForTrait( AttributeFormatterTrait::class )
				->get_attributes_as_string( $input )
		);
	}

	public function provide_get_attributes_as_string() {

		return [
			'empty attributes'   => [ [], '' ],
			'string attributes'  => [ [ 'foo' => 'bar' ], 'foo="bar"' ],
			'int attributes'     => [ [ 1 => 2 ], '1="2"' ],
			'boolean attributes' => [ [ 'disabled' => TRUE, 'required' => FALSE ], 'disabled' ],
			'array attributes'   => [ [ 'foo' => [ 'bar' => 'baz' ] ], 'foo="{"bar":"baz"}"' ]
		];
	}

}