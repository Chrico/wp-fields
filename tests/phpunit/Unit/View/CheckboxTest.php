<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Checkbox;
use ChriCo\Fields\View\RenderableElementInterface;

class CheckboxTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Checkbox();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
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

        (new Checkbox())->render($stub);
    }

    /**
     * Test rendering of an CheckBox with empty ChoiceList.
     */
    public function test_render__no_choices()
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
    private function get_element(string $name, ChoiceListInterface $list)
    {

        $element = new ChoiceElement($name);
        $element->withAttribute('type', 'checkbox');
        $element->withChoices($list);

        return $element;
    }

    /**
     * Test rendering of an CheckBox with 1 item in ChoiceList.
     */
    public function test_render__one_choice()
    {

        $element = $this->get_element('element', new ArrayChoiceList(['foo' => 'bar']));

        $rendered = (new Checkbox())->render($element);
        static::assertContains('name="element"', $rendered);
        static::assertContains('id="element"', $rendered);
        static::assertContains('for="element"', $rendered);
        static::assertContains('value="foo"', $rendered);
    }

    /**
     * Test rendering of an CheckBox with 1 item in ChoiceList which is checked.
     */
    public function test_render__one_choice_checked()
    {

        $expected_value = 'foo';
        $element        = $this->get_element('element', new ArrayChoiceList([$expected_value => 'bar']));
        $element->withValue($expected_value);

        static::assertContains('checked="checked"', (new Checkbox())->render($element));
    }

    /**
     * Test rendering of an CheckBox with multiple items in ChoiceList.
     */
    public function test_render__multiple_choices()
    {

        $element = $this->get_element('element', new ArrayChoiceList(['foo' => 'bar', 'baz' => 'bam']));

        $rendered = (new Checkbox())->render($element);
        // both elements are having this name.
        static::assertContains('name="element[]"', $rendered);

        // first element
        static::assertContains('value="foo"', $rendered);
        static::assertContains('id="element_foo"', $rendered);
        static::assertContains('for="element_foo"', $rendered);

        // second element
        static::assertContains('value="baz"', $rendered);
        static::assertContains('id="element_baz"', $rendered);
        static::assertContains('for="element_baz"', $rendered);
    }

}