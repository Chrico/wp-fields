<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Radio;
use ChriCo\Fields\View\RenderableElementInterface;

class RadioTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Radio();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\InvalidClassException
     */
    public function test_render__invalid_element()
    {

        (new Radio())->render(
            $this->getMockBuilder(ElementInterface::class)
                ->getMock()
        );
    }

    /**
     * Test rendering of an CheckBox with empty ChoiceList.
     */
    public function test_render__no_choices()
    {

        $element = $this->element('element', new ArrayChoiceList([]));
        static::assertSame('', (new Radio())->render($element));
    }

    /**
     * Internal function to create a new ChoiceElement with type="radio".
     *
     * @param string              $name
     * @param ChoiceListInterface $list
     *
     * @return ChoiceElement
     */
    private function element(string $name, ChoiceListInterface $list)
    {

        $element = new ChoiceElement($name);
        $element->withAttribute('type', 'radio');
        $element->withChoices($list);

        return $element;
    }

    /**
     * Test rendering of an Radio with 1 item in ChoiceList.
     */
    public function test_render__one_choice()
    {

        $element = $this->element('element', new ArrayChoiceList(['foo' => "bar"]));

        $rendered = (new Radio())->render($element);
        static::assertContains('name="element"', $rendered);
        static::assertContains('value="foo"', $rendered);

        static::assertContains('<label', $rendered);
        static::assertContains('for="element_foo"', $rendered);
        static::assertContains('bar</label>', $rendered);
    }

    /**
     * Test rendering of an Radio with 1 item in ChoiceList which is checked.
     */
    public function test_render__one_choice_checked()
    {

        $expected_value = 'foo';
        $element        = $this->element('element', new ArrayChoiceList([$expected_value => 'bar']));
        $element->withValue($expected_value);

        static::assertContains('checked="checked"', (new Radio())->render($element));
    }

    /**
     * Test rendering of an Radio with multiple items in ChoiceList.
     */
    public function test_render__multiple_choices()
    {

        $element = $this->element('element', new ArrayChoiceList(['foo' => 'bar', 'baz' => 'bam']));

        $rendered = (new Radio())->render($element);
        // both elements are having this name.
        static::assertContains('name="element"', $rendered);

        // first element
        static::assertContains('value="foo"', $rendered);
        static::assertContains('id="element_foo"', $rendered);

        static::assertContains('<label', $rendered);
        static::assertContains('for="element_foo"', $rendered);
        static::assertContains('bar</label>', $rendered);

        // second element
        static::assertContains('value="baz"', $rendered);
        static::assertContains('id="element_baz"', $rendered);

        static::assertContains('<label', $rendered);
        static::assertContains('for="element_baz"', $rendered);
        static::assertContains('bam</label>', $rendered);
    }

}