<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class ChoiceElementTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new ChoiceElement('name');
        static::assertInstanceOf(LabelAwareInterface::class, $testee);
        static::assertInstanceOf(ElementInterface::class, $testee);
        static::assertInstanceOf(DescriptionAwareInterface::class, $testee);
        static::assertInstanceOf(ErrorAwareInterface::class, $testee);

        $list = $testee->get_choices();
        static::assertInstanceOf(ArrayChoiceList::class, $list);

        static::assertEmpty($list->get_choices());
    }

    /**
     * Test set and get choices methods.
     */
    public function test_set_get_choices()
    {

        $expected = new ArrayChoiceList();
        $testee   = new ChoiceElement('name');
        $testee->set_choices($expected);

        static::assertSame($expected, $testee->get_choices());
    }

}