<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\View\Button;
use ChriCo\Fields\View\RenderableElementInterface;

class ButtonTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Button();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @test
     */
    public function testRenderTypeButton(): void
    {
        $expectedLabel = 'some label';

        $element = new Element('foo');
        $element->withAttribute('type', 'button');
        $element->withLabel($expectedLabel);

        $output = (new Button())->render($element);
        static::assertStringContainsString('<button', $output);
        static::assertStringContainsString('name="foo"', $output);
        static::assertStringContainsString('type="button"', $output);
        static::assertStringContainsString($expectedLabel, $output);
        static::assertStringContainsString('</button>', $output);
    }

    /**
     * @test
     */
    public function testRenderTypeReset(): void
    {
        $expectedLabel = 'some label';

        $element = new Element('foo');
        $element->withAttribute('type', 'reset');
        $element->withLabel($expectedLabel);

        $output = (new Button())->render($element);
        static::assertStringContainsString('<button', $output);
        static::assertStringContainsString('name="foo"', $output);
        static::assertStringContainsString('type="reset"', $output);
        static::assertStringContainsString($expectedLabel, $output);
        static::assertStringContainsString('</button>', $output);
    }
}
