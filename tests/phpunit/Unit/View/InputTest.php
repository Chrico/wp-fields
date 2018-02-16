<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\Input;
use ChriCo\Fields\View\RenderableElementInterface;

class InputTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Input();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     */
    public function test_render()
    {

        $element = new Element('foo');
        $element->set_attribute('type', 'text');

        $output = (new Input())->render($element);
        static::assertContains('<input', $output);
        static::assertContains('name="foo"', $output);
        static::assertContains('type="text"', $output);
        static::assertContains('/>', $output);
    }
}