<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Label;
use ChriCo\Fields\View\RenderableElementInterface;

class LabelTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Label();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     * @test
     */
    public function test_render(): void
    {

        $expected_attr  = ['for' => 'foo'];
        $expected_label = 'Foo';

        $element = new Element('name');
        $element->withLabel($expected_label);
        $element->withLabelAttributes($expected_attr);

        $output = (new Label())->render($element);
        static::assertStringContainsString('<label', $output);
        static::assertStringContainsString('</label>', $output);
        static::assertStringContainsString('for="foo"', $output);
        static::assertStringContainsString('>Foo</label>', $output);
    }

    /**
     * @test
     */
    public function test_render__invalid_element(): void
    {
        static::expectException(InvalidClassException::class);
        /** @var \Mockery\MockInterface|ElementInterface $stub */
        $stub = \Mockery::mock(ElementInterface::class);
        $stub->allows('name')
            ->andReturn('');

        (new Label())->render($stub);
    }

    /**
     * Test if the "for"-attribute is automatically used from element id if not set.
     * @test
     */
    public function test_render__for_attribute(): void
    {

        $expected_name  = 'name';
        $expected_label = 'Foo';

        $element = new Element($expected_name);
        $element->withLabel($expected_label);

        $output = (new Label())->render($element);
        static::assertStringContainsString('for="' . $expected_name . '"', $output);
        static::assertStringContainsString('>' . $expected_label . '</label>', $output);

    }
}
