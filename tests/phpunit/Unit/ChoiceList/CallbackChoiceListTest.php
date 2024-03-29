<?php # -*- coding: utf-8 -*-

namespace ChriCo\Tests\Fields\ChoiceList;

use ChriCo\Fields\ChoiceList\CallbackChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class CallbackChoiceListTest extends AbstractTestCase
{
    /**
     * Basic test with default class behavior.
     *
     * @test
     */
    public function testBasic(): void
    {
        $callback = function () {
        };
        $testee = new CallbackChoiceList($callback);

        static::assertInstanceOf(ChoiceListInterface::class, $testee);
        static::assertFalse($testee->isLoaded());
    }

    /**
     * Test if we can use a simple closoure to load some results.
     *
     * @test
     */
    public function testGetChoices(): void
    {
        $expected = [
            'foo' => [
                'value' => 'foo',
                'label' => 'Foo',
                'disabled' => false,
            ],
        ];

        $callback = function () use ($expected) {
            return $expected;
        };

        $testee = new CallbackChoiceList($callback);
        static::assertSame($expected, $testee->choices());
        static::assertTrue($testee->isLoaded());

        // trigger again should not reload choices.
        static::assertSame($expected, $testee->choices());
    }
}
