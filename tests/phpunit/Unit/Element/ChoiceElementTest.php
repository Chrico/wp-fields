<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\Element\DescriptionAwareInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Element\LabelAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ChoiceElementTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new ChoiceElement('name');
        static::assertInstanceOf(LabelAwareInterface::class, $testee);
        static::assertInstanceOf(ElementInterface::class, $testee);
        static::assertInstanceOf(DescriptionAwareInterface::class, $testee);
        static::assertInstanceOf(ErrorAwareInterface::class, $testee);

        $list = $testee->choices();
        static::assertInstanceOf(ArrayChoiceList::class, $list);

        static::assertEmpty($list->choices());
    }

    /**
     * Test set and get choices methods.
     * @test
     */
    public function test_set_get_choices(): void
    {

        $expected = new ArrayChoiceList();
        $testee   = new ChoiceElement('name');
        $testee->withChoices($expected);

        static::assertSame($expected, $testee->choices());
    }
}
