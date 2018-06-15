<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ElementTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavoir of the class.
     */
    public function test_basic()
    {

        $expected_name = 'foo';
        $testee        = new Element($expected_name);

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
     */
    public function test_set_get_description()
    {

        $expected = 'test';

        $testee = new Element('id');
        $testee->withDescription($expected);

        static::assertSame($expected, $testee->description());
    }

    /**
     * Test if we can set and get again the label.
     */
    public function test_set_get_label()
    {

        $expected = 'test';

        $testee = new Element('id');
        $testee->withLabel($expected);

        static::assertSame($expected, $testee->label());
    }

    /**
     * Test if we can set and get again the label attributes.
     */
    public function test_set_get_label_attributes()
    {

        $expected = ['foo' => 'bar'];

        $testee = new Element('id');
        $testee->withLabelAttributes($expected);

        static::assertSame($expected, $testee->labelAttributes());
    }

    /**
     * Test if we can set and get again the value.
     */
    public function test_set_get_value()
    {

        $expected = 'test';

        $testee = new Element('id');
        $testee->withValue($expected);

        static::assertSame($expected, $testee->value());
    }

    /**
     * Test if we can set and get again the errors.
     */
    public function test_set_get_errors()
    {

        $expected = ['foo' => 'bar', 'baz' => 'bam'];

        $testee = new Element('id');
        $testee->withErrors($expected);

        static::assertSame($expected, $testee->errors());
        static::assertTrue($testee->hasErrors());
    }

    public function test_set_get_attributes()
    {

        $expected_id = 'id';
        $expected    = ['name' => 'text', 'type' => 'bam'];

        $testee = new Element($expected_id);
        $testee->withAttributes($expected);

        $attributes = $testee->attributes();

        static::assertArrayHasKey('name', $attributes);
        static::assertArrayHasKey('type', $attributes);
        static::assertArrayHasKey('id', $attributes);
    }

    public function test_set_get_options()
    {

        $expected = ['name' => 'text', 'type' => 'bam'];

        $testee = new Element('id');
        $testee->withOptions($expected);

        static::assertSame($expected, $testee->options());
    }

    /**
     * Basic test to check, if we can set and get a single option.
     */
    public function test_set_get_option()
    {

        $testee = new Element('id');
        $testee->withOption('foo', 'bar');

        static::assertSame('bar', $testee->option('foo'));
        static::assertSame(['foo' => 'bar'], $testee->options());
        static::assertSame('', $testee->option('undefined key'));
    }

    public function test_is_disabled()
    {

        $testee = new Element('');
        $testee->withAttribute('disabled', true);

        static::assertTrue($testee->isDisabled());
    }
}