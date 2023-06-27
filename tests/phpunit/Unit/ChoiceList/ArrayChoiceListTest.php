<?php # -*- coding: utf-8 -*-

namespace ChriCo\Tests\Fields\ChoiceList;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;
use Generator;

class ArrayChoiceListTest extends AbstractTestCase
{
    /**
     * Basic test with default class behavior.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new ArrayChoiceList();

        static::assertInstanceOf(ChoiceListInterface::class, $testee);
        static::assertEmpty($testee->choices());
        static::assertEmpty($testee->values());
        static::assertEmpty($testee->choicesForValue());
    }

    /**
     * @test
     */
    public function testGetChoices(): void
    {
        $input = ['foo' => 'bar'];
        $expected = [
            'foo' => [
                'value' => 'foo',
                'label' => 'bar',
                'disabled' => false
            ]
        ];
        $testee = new ArrayChoiceList($input);

        static::assertSame($expected, $testee->choices());
    }

    /**
     * @param array $input
     * @param array $expected
     *
     * @dataProvider provideGetValues
     * @test
     */
    public function testGetValues($input, $expected): void
    {
        static::assertSame($expected, (new ArrayChoiceList($input))->values());
    }

    public function provideGetValues(): Generator
    {
        yield 'string values' => [
            ['foo' => 'bar'],
            ['foo']
        ];


        yield 'int values' => [
            [0 => 'foo', 1 => 'bar'],
            ['0', '1']
        ];
    }

    /**
     * @param array $list
     * @param array $selected
     * @param array $expected
     *
     * @dataProvider provideGetChoicesForValue
     * @test
     */
    public function testGetChoicesForValue($list, $selected, $expected): void
    {
        static::assertSame($expected, (new ArrayChoiceList($list))->choicesForValue($selected));
    }

    public function provideGetChoicesForValue(): Generator
    {
        $option1 = [
            'value' => 'foo',
            'label' => 'Foo',
            'disabled' => false,
        ];
        $option2 = [
            'value' => 'bar',
            'label' => 'Bar',
            'disabled' => false,
        ];

        yield '1 selected' => [
            ['foo' => $option1, 'bar' => $option2],
            ['foo'],
            ['foo' => $option1],
        ];

        yield 'multiple selected' => [
            ['foo' => $option1, 'bar' => $option2],
            ['foo', 'bar'],
            ['foo' => $option1, 'bar' => $option2],
        ];

        yield 'nothing selected' => [
            ['foo' => $option1, 'bar' => $option2],
            [],
            [],
        ];

        yield 'everything empty' => [
            [],
            [],
            [],
        ];

        yield 'empty list' => [
            [],
            ['foo'],
            [],
        ];
    }
}
