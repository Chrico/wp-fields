<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\RenderableElementInterface;
use ChriCo\Fields\View\Select;

class SelectTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Select();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @test
     */
    public function testRenderInvalidElement(): void
    {
        static::expectException(InvalidClassException::class);
        (new Select())->render(
            $this->getMockBuilder(ElementInterface::class)
                ->getMock()
        );
    }

    /**
     * Test rendering of an Select with empty ChoiceList.
     *
     * @test
     */
    public function testRenderNoChoices(): void
    {
        $element = $this->element('element', new ArrayChoiceList([]));
        $rendered = (new Select())->render($element);

        static::assertStringContainsString('<select', $rendered);
        static::assertStringContainsString('name="element"', $rendered);
        static::assertStringContainsString('id="element"', $rendered);
        static::assertStringContainsString('</select>', $rendered);
    }

    /**
     * Internal function to create a new ChoiceElement with type="select".
     *
     * @param string $name
     * @param ChoiceListInterface $list
     *
     * @return ChoiceElement
     */
    private function element(string $name, ChoiceListInterface $list): ChoiceElement
    {
        $element = new ChoiceElement($name);
        $element->withAttribute('type', 'select');
        $element->withChoices($list);

        return $element;
    }

    /**
     * Test rendering of an Select with 1 item in ChoiceList.
     *
     * @test
     */
    public function testRenderOneChoice(): void
    {
        $element = $this->element('element', new ArrayChoiceList(['foo' => 'bar']));

        $rendered = (new Select())->render($element);

        static::assertStringContainsString('<select', $rendered);
        static::assertStringContainsString('name="element"', $rendered);

        static::assertStringContainsString('<option', $rendered);
        static::assertStringContainsString('value="foo"', $rendered);
        static::assertStringContainsString('bar</option>', $rendered);

        static::assertStringContainsString('</select>', $rendered);
    }

    /**
     * Test rendering of an Select with 1 item in ChoiceList which is selected.
     *
     * @test
     */
    public function testRenderOneChoiceSelected(): void
    {
        $expected_value = 'foo';
        $element = $this->element('element', new ArrayChoiceList([$expected_value => 'bar']));
        $element->withValue($expected_value);

        static::assertStringContainsString('selected="selected"', (new Select())->render($element));
    }

    /**
     * Test rendering of an Select with multiple items in ChoiceList.
     *
     * @test
     */
    public function testRenderMultipleChoices(): void
    {
        $element = $this->element('element', new ArrayChoiceList(['foo' => 'bar', 'baz' => 'bam']));

        $rendered = (new Select())->render($element);
        static::assertStringContainsString('<select', $rendered);
        static::assertStringContainsString('name="element"', $rendered);

        // First element
        static::assertStringContainsString('<option', $rendered);
        static::assertStringContainsString('value="foo"', $rendered);
        static::assertStringContainsString('bar</option>', $rendered);

        // second element
        static::assertStringContainsString('<option', $rendered);
        static::assertStringContainsString('value="baz"', $rendered);
        static::assertStringContainsString('bam</option>', $rendered);

        static::assertStringContainsString('</select>', $rendered);
    }
}
