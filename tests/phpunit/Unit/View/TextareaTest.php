<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\RenderableElementInterface;
use ChriCo\Fields\View\Textarea;

class TextareaTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Textarea();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     */
    public function test_render()
    {

        $element = new Element('foo');
        $element->withValue('100');

        $output = (new Textarea())->render($element);
        static::assertContains('<textarea', $output);
        static::assertContains('name="foo"', $output);
        static::assertContains('>100</textarea>', $output);
    }
}