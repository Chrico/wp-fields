<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Checkbox;
use ChriCo\Fields\View\RenderableElementInterface;

class CheckboxTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Checkbox();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
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

        (new Checkbox())->render($stub);
    }

    /**
     * Test rendering of an CheckBox with empty ChoiceList.
     * @test
     */
    public function test_render__no_choices(): void
    {

        $element = $this->get_element('element', new ArrayChoiceList([]));
        static::assertSame('', (new Checkbox())->render($element));
    }

    /**
     * Internal function to create a new ChoiceElement with type="checkbox".
     *
     * @param string              $name
     * @param ChoiceListInterface $list
     *
     * @return ChoiceElement
     */
    private function get_element(string $name, ChoiceListInterface $list): ChoiceElement
    {

        $element = new ChoiceElement($name);
        $element->withAttribute('type', 'checkbox');
        $element->withChoices($list);

        return $element;
    }

    /**
     * Test rendering of an CheckBox with 1 item in ChoiceList.
     * @test
     */
    public function test_render__one_choice(): void
    {

        $element = $this->get_element('element', new ArrayChoiceList(['foo' => 'bar']));

        $rendered = (new Checkbox())->render($element);
        static::assertStringContainsString('name="element"', $rendered);
        static::assertStringContainsString('id="element"', $rendered);
        static::assertStringContainsString('for="element"', $rendered);
        static::assertStringContainsString('value="foo"', $rendered);
    }

    /**
     * Test rendering of an CheckBox with 1 item in ChoiceList which is checked.
     * @test
     */
    public function test_render__one_choice_checked(): void
    {

        $expected_value = 'foo';
        $element        = $this->get_element('element', new ArrayChoiceList([$expected_value => 'bar']));
        $element->withValue($expected_value);

        static::assertStringContainsString('checked="checked"', (new Checkbox())->render($element));
    }

    /**
     * Test rendering of an CheckBox with multiple items in ChoiceList.
     * @test
     */
    public function test_render__multiple_choices(): void
    {

        $element = $this->get_element('element', new ArrayChoiceList(['foo' => 'bar', 'baz' => 'bam']));

        $rendered = (new Checkbox())->render($element);
        // both elements are having this name.
        static::assertStringContainsString('name="element[]"', $rendered);

        // first element
        static::assertStringContainsString('value="foo"', $rendered);
        static::assertStringContainsString('id="element_foo"', $rendered);
        static::assertStringContainsString('for="element_foo"', $rendered);

        // second element
        static::assertStringContainsString('value="baz"', $rendered);
        static::assertStringContainsString('id="element_baz"', $rendered);
        static::assertStringContainsString('for="element_baz"', $rendered);
    }
}
