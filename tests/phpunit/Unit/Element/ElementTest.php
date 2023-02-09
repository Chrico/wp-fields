<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\LabelAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ElementTest extends AbstractTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $expected_name = 'foo';
        $testee = new Element($expected_name);

        static::assertInstanceOf(ElementInterface::class, $testee);
        static::assertInstanceOf(LabelAwareInterface::class, $testee);

        static::assertSame($expected_name, $testee->id());
        static::assertSame($expected_name, $testee->name());
        static::assertEmpty($testee->type());
        static::assertEmpty($testee->value());

        static::assertEmpty($testee->label());
        static::assertCount(0, $testee->labelAttributes());

        static::assertCount(2, $testee->attributes());
        static::assertSame(['name' => $expected_name, 'id' => $expected_name], $testee->attributes());

        static::assertCount(0, $testee->options());

        static::assertCount(0, $testee->errors());
        static::assertFalse($testee->hasErrors());

        static::assertFalse($testee->isDisabled());
    }

    /**
     * Test if we can set and get again the description.
     *
     * @test
     */
    public function testSetGetDescription(): void
    {
        $expected = 'test';

        $testee = new Element('id');
        $testee->withDescription($expected);

        static::assertSame($expected, $testee->description());
    }

    /**
     * Test if we can set and get again the label.
     *
     * @test
     */
    public function testSetGetLabel(): void
    {
        $expected = 'test';

        $testee = new Element('id');
        $testee->withLabel($expected);

        static::assertSame($expected, $testee->label());
    }

    /**
     * Test if we can set and get again the label attributes.
     *
     * @test
     */
    public function testSetGetLabelAttributes(): void
    {
        $expected = ['foo' => 'bar'];

        $testee = new Element('id');
        $testee->withLabelAttributes($expected);

        static::assertSame($expected, $testee->labelAttributes());
    }

    /**
     * Test if we can set and get again the value.
     *
     * @test
     */
    public function testSetGetValue(): void
    {
        $expected = 'test';

        $testee = new Element('id');
        $testee->withValue($expected);

        static::assertSame($expected, $testee->value());
    }

    /**
     * Test if we can set and get again the errors.
     *
     * @test
     */
    public function testSetGetErrors(): void
    {
        $expected = ['foo' => 'bar', 'baz' => 'bam'];

        $testee = new Element('id');
        $testee->withErrors($expected);

        static::assertSame($expected, $testee->errors());
        static::assertTrue($testee->hasErrors());
    }

    /**
     * @test
     */
    public function testSetGetAttributes(): void
    {
        $expected_id = 'id';
        $expected = ['name' => 'text', 'type' => 'bam'];

        $testee = new Element($expected_id);
        $testee->withAttributes($expected);

        $attributes = $testee->attributes();

        static::assertArrayHasKey('name', $attributes);
        static::assertArrayHasKey('type', $attributes);
        static::assertArrayHasKey('id', $attributes);
    }

    /**
     * @test
     */
    public function testSetGetOptions(): void
    {
        $expected = ['name' => 'text', 'type' => 'bam'];

        $testee = new Element('id');
        $testee->withOptions($expected);

        static::assertSame($expected, $testee->options());
    }

    /**
     * Basic test to check, if we can set and get a single option.
     *
     * @test
     */
    public function testSetGetOption(): void
    {
        $testee = new Element('id');
        $testee->withOption('foo', 'bar');

        static::assertSame('bar', $testee->option('foo'));
        static::assertSame(['foo' => 'bar'], $testee->options());
        static::assertSame('', $testee->option('undefined key'));
    }

    /**
     * @test
     */
    public function testIsDisabled(): void
    {
        $testee = new Element('');
        $testee->withAttribute('disabled', true);

        static::assertTrue($testee->isDisabled());
    }
}
