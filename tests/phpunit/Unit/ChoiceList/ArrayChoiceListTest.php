<?php # -*- coding: utf-8 -*-

namespace ChriCo\Tests\Fields\ChoiceList;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ArrayChoiceListTest extends AbstractTestCase {

	/**
	 * Basic test with default class behavior.
	 */
	public function test_basic() {

		$testee = new ArrayChoiceList();

		static::assertInstanceOf( ChoiceListInterface::class, $testee );
		static::assertEmpty( $testee->get_choices() );
		static::assertEmpty( $testee->get_values() );
		static::assertEmpty( $testee->get_choices_for_value() );

	}

	public function test_get_choices() {

		$expected = [ 'foo' => 'bar' ];
		$testee   = new ArrayChoiceList( $expected );

		static::assertSame( $expected, $testee->get_choices() );
	}

	/**
	 * @param array $input
	 * @param array $expected
	 *
	 * @dataProvider provide_get_values
	 */
	public function test_get_values( $input, $expected ) {

		static::assertSame( $expected, ( new ArrayChoiceList( $input ) )->get_values() );
	}

	/**
	 * @return array
	 */
	public function provide_get_values() {

		return [
			'string values' => [ [ 'foo' => 'bar' ], [ 'foo' ] ],
			'int values'    => [ [ 0 => 'foo', 1 => 'bar' ], [ '0', '1' ] ]
		];
	}

	/**
	 * @param array $list
	 * @param array $selected
	 * @param array $expected
	 *
	 * @dataProvider provide_get_choices_for_value
	 */
	public function test_get_choices_for_value( $list, $selected, $expected ) {

		static::assertSame( $expected, ( new ArrayChoiceList( $list ) )->get_choices_for_value( $selected ) );

	}

	public function provide_get_choices_for_value() {

		return [
			'1 selected'        => [
				[ 'foo' => 'bar', 'baz' => 'bam' ],
				[ 'foo' ],
				[ 'foo' => 'bar' ]
			],
			'multiple selected' => [
				[ 'foo' => 'bar', 'baz' => 'bam' ],
				[ 'foo', 'baz' ],
				[ 'foo' => 'bar', 'baz' => 'bam' ]
			],
			'nothing selected'  => [
				[ 'foo' => 'bar', 'baz' => 'bam' ],
				[],
				[]
			],
			'everything empty'  => [
				[],
				[],
				[]
			],
			'empty list'        => [
				[],
				[ 'foo' => 'bar' ],
				[]
			]
		];
	}
}