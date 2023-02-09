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
        $expected = ['foo' => 'bar'];
        $testee = new ArrayChoiceList($expected);

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
        yield 'string values' => [['foo' => 'bar'], ['foo']];
        yield 'int values' => [[0 => 'foo', 1 => 'bar'], ['0', '1']];
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
        yield '1 selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            ['foo'],
            ['foo' => 'bar'],
        ];

        yield 'multiple selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            ['foo', 'baz'],
            ['foo' => 'bar', 'baz' => 'bam'],
        ];

        yield 'nothing selected' => [
            ['foo' => 'bar', 'baz' => 'bam'],
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
            ['foo' => 'bar'],
            [],
        ];
    }
}
