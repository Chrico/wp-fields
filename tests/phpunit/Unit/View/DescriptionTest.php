<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Description;
use ChriCo\Fields\View\RenderableElementInterface;

class DescriptionTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Description();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     *
     */
    public function test_render()
    {

        $expected = 'lorum ipsum';

        $element = new Element('name');
        $element->withDescription($expected);

        $output = (new Description())->render($element);
        static::assertContains('<p', $output);
        static::assertContains('>' . $expected, $output);
        static::assertContains('</p>', $output);
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\InvalidClassException
     */
    public function test_render__invalid_element()
    {

        /** @var \Mockery\MockInterface|ElementInterface $stub */
        $stub = \Mockery::mock(ElementInterface::class);
        $stub->shouldReceive('name')
            ->andReturn('');

        (new Description())->render($stub);
    }

}