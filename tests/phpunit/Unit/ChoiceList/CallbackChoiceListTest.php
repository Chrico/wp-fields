<?php # -*- coding: utf-8 -*-

namespace ChriCo\Tests\Fields\ChoiceList;

use ChriCo\Fields\ChoiceList\CallbackChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class CallbackChoiceListTest extends AbstractTestCase
{

    /**
     * Basic test with default class behavior.
     */
    public function test_basic()
    {

        $callback = function () {
        };
        $testee   = new CallbackChoiceList($callback);

        static::assertInstanceOf(ChoiceListInterface::class, $testee);
        static::assertFalse($testee->is_loaded());

    }

    /**
     * Test if we can use a simple closoure to load some results.
     */
    public function test_get_choices()
    {

        $expected = ['foo' => 'bar'];

        $callback = function () use ($expected) {

            return $expected;
        };

        $testee = new CallbackChoiceList($callback);
        static::assertSame($expected, $testee->get_choices());
        static::assertTrue($testee->is_loaded());
    }

}