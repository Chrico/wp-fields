<?php # -*- coding: utf-8 -*-

namespace ChriCo\Tests\Fields\ChoiceList;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ArrayChoiceListTest extends AbstractTestCase
{

    /**
     * Basic test with default class behavior.
     */
    public function test_basic()
    {

        $testee = new ArrayChoiceList();

        static::assertInstanceOf(ChoiceListInterface::class, $testee);
        static::assertEmpty($testee->choices());
        static::assertEmpty($testee->values());
        static::assertEmpty($testee->choicesForValue());

    }

    public function test_get_choices()
    {

        $expected = ['foo' => 'bar'];
        $testee   = new ArrayChoiceList($expected);

        static::assertSame($expected, $testee->choices());
    }

    /**
     * @param array $input
     * @param array $expected
     *
     * @dataProvider provide_get_values
     */
    public function test_get_values($input, $expected)
    {

        static::assertSame($expected, (new ArrayChoiceList($input))->values());
    }

    public function provide_get_values()
    {

        yield 'string values' => [['foo' => 'bar'], ['foo']];
        yield 'int values' => [[0 => 'foo', 1 => 'bar'], ['0', '1']];
    }

    /**
     * @param array $list
     * @param array $selected
     * @param array $expected
     *
     * @dataProvider provide_get_choices_for_value
     */
    public function test_get_choices_for_value($list, $selected, $expected)
    {

        static::assertSame($expected, (new ArrayChoiceList($list))->choicesForValue($selected));

    }

    public function provide_get_choices_for_value()
    {

        yield '1 selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            ['foo'],
            ['foo' => 'bar']
        ];

        yield 'multiple selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            ['foo', 'baz'],
            ['foo' => 'bar', 'baz' => 'bam']
        ];

        yield 'nothing selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            [],
            []
        ];

        yield 'everything empty' => [
            [],
            [],
            []
        ];

        yield 'empty list' => [
            [],
            ['foo' => 'bar'],
            []
        ];
    }
}