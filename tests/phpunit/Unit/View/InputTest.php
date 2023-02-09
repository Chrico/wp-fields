<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\Input;
use ChriCo\Fields\View\RenderableElementInterface;

class InputTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Input();
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
        $element->withAttribute('type', 'text');

        $output = (new Input())->render($element);
        static::assertStringContainsString('<input', $output);
        static::assertStringContainsString('name="foo"', $output);
        static::assertStringContainsString('type="text"', $output);
        static::assertStringContainsString('/>', $output);
    }
}
