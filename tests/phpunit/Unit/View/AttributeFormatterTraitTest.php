<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\View\AttributeFormatterTrait;

class AttributeFormatterTraitTest extends AbstractViewTestCase
{

    /**
     * @dataProvider provide_get_attributes_as_string
     * @test
     */
    public function test_get_attributes_as_string(array $input, string $expected): void
    {

        static::assertSame(
            $expected,
            /** @var AttributeFormatterTrait $testee */
            $this->getMockForTrait(AttributeFormatterTrait::class)
                ->attributesToString($input)
        );
    }

    public function provide_get_attributes_as_string(): \Generator
    {

        yield 'empty attributes' => [[], ''];
        yield 'string attributes' => [['foo' => 'bar'], 'foo="bar"'];
        yield 'int attributes' => [[1 => 2], '1="2"'];
        yield 'boolean attributes' => [['disabled' => true, 'required' => false], 'disabled="disabled"'];
        yield 'array attributes' => [['foo' => ['bar' => 'baz']], 'foo="{"bar":"baz"}"'];
        yield 'multiple attributes' => [['foo' => 'bar', 'baz' => 'bam'], 'foo="bar" baz="bam"'];

    }
}
