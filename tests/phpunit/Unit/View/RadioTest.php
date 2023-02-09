<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Radio;
use ChriCo\Fields\View\RenderableElementInterface;

class RadioTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Radio();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @test
     */
    public function testRenderInvalidElement(): void
    {
        static::expectException(InvalidClassException::class);
        (new Radio())->render(
            $this->getMockBuilder(ElementInterface::class)
                ->getMock()
        );
    }

    /**
     * Test rendering of an CheckBox with empty ChoiceList.
     *
     * @test
     */
    public function testRenderNoChoices(): void
    {
        $element = $this->element('element', new ArrayChoiceList([]));
        static::assertSame('', (new Radio())->render($element));
    }

    /**
     * Internal function to create a new ChoiceElement with type="radio".
     *
     * @param string $name
     * @param ChoiceListInterface $list
     *
     * @return ChoiceElement
     */
    private function element(string $name, ChoiceListInterface $list): ChoiceElement
    {
        $element = new ChoiceElement($name);
        $element->withAttribute('type', 'radio');
        $element->withChoices($list);

        return $element;
    }

    /**
     * Test rendering of an Radio with 1 item in ChoiceList.
     *
     * @test
     */
    public function testRenderOneChoice(): void
    {
        $element = $this->element('element', new ArrayChoiceList(['foo' => "bar"]));

        $rendered = (new Radio())->render($element);
        static::assertStringContainsString('name="element"', $rendered);
        static::assertStringContainsString('value="foo"', $rendered);

        static::assertStringContainsString('<label', $rendered);
        static::assertStringContainsString('for="element_foo"', $rendered);
        static::assertStringContainsString('bar</label>', $rendered);
    }

    /**
     * Test rendering of an Radio with 1 item in ChoiceList which is checked.
     *
     * @test
     */
    public function testRenderOneChoiceChecked(): void
    {
        $expected_value = 'foo';
        $element = $this->element('element', new ArrayChoiceList([$expected_value => 'bar']));
        $element->withValue($expected_value);

        static::assertStringContainsString('checked="checked"', (new Radio())->render($element));
    }

    /**
     * Test rendering of an Radio with multiple items in ChoiceList.
     *
     * @test
     */
    public function testRenderMultipleChoices(): void
    {
        $element = $this->element('element', new ArrayChoiceList(['foo' => 'bar', 'baz' => 'bam']));

        $rendered = (new Radio())->render($element);
        // both elements are having this name.
        static::assertStringContainsString('name="element"', $rendered);

        // first element
        static::assertStringContainsString('value="foo"', $rendered);
        static::assertStringContainsString('id="element_foo"', $rendered);

        static::assertStringContainsString('<label', $rendered);
        static::assertStringContainsString('for="element_foo"', $rendered);
        static::assertStringContainsString('bar</label>', $rendered);

        // second element
        static::assertStringContainsString('value="baz"', $rendered);
        static::assertStringContainsString('id="element_baz"', $rendered);

        static::assertStringContainsString('<label', $rendered);
        static::assertStringContainsString('for="element_baz"', $rendered);
        static::assertStringContainsString('bam</label>', $rendered);
    }
}
