<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\RenderableElementInterface;
use ChriCo\Fields\View\Textarea;

class TextareaTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Textarea();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     * @test
     */
    public function test_render(): void
    {
        $element = new Element('foo');
        $element->withValue('100');

        $output = (new Textarea())->render($element);
        static::assertStringContainsString('<textarea', $output);
        static::assertStringContainsString('name="foo"', $output);
        static::assertStringContainsString('>100</textarea>', $output);
    }
}
