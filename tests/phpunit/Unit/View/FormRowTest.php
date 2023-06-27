<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\FormRow;
use ChriCo\Fields\View\RenderableElementInterface;

class FormRowTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new FormRow();
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

        $output = (new FormRow())->render($element);
        static::assertStringContainsString('<div', $output);
        static::assertStringContainsString('class="form-row', $output);
        static::assertStringContainsString('</div>', $output);
    }

    /**
     * Test rendering of an Element with label.
     *
     * @test
     */
    public function testRenderWithLabel(): void
    {
        $expected_label = 'bar';

        $element = new Element('foo');
        $element->withAttribute('type', 'text');
        $element->withLabel($expected_label);

        $output = (new FormRow())->render($element);
        static::assertStringContainsString('<div', $output);
        static::assertStringContainsString('class="form-row', $output);
        static::assertStringContainsString('<label', $output);
        static::assertStringContainsString($expected_label . '</label>', $output);
        static::assertStringContainsString('</div>', $output);
    }
}
