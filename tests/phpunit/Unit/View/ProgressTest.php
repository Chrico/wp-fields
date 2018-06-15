<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\Progress;
use ChriCo\Fields\View\RenderableElementInterface;

class ProgressTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Progress();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     */
    public function test_render()
    {

        $element = new Element('foo');
        $element->withValue('100');

        $output = (new Progress())->render($element);
        static::assertContains('</progress>', $output);
        static::assertContains('name="foo"', $output);
        static::assertContains('>100</progress>', $output);
    }
}