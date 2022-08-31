<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Description;
use ChriCo\Fields\View\RenderableElementInterface;

class DescriptionTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Description();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     * @test
     */
    public function test_render(): void
    {

        $expected = 'lorum ipsum';

        $element = new Element('name');
        $element->withDescription($expected);

        $output = (new Description())->render($element);
        static::assertStringContainsString('<p', $output);
        static::assertStringContainsString('>' . $expected, $output);
        static::assertStringContainsString('</p>', $output);
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

        (new Description())->render($stub);
    }
}
