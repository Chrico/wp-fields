<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\Progress;
use ChriCo\Fields\View\RenderableElementInterface;

class ProgressTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Progress();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     *
     * @test
     */
    public function testRender(): void
    {
        $element = new Element('foo');
        $element->withValue('100');

        $output = (new Progress())->render($element);
        static::assertStringContainsString('</progress>', $output);
        static::assertStringContainsString('name="foo"', $output);
        static::assertStringContainsString('>100</progress>', $output);
    }
}
