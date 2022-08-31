<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit;

use ChriCo\Fields\Exception\UnknownTypeException;
use ChriCo\Fields\Tests\Unit\Fixtures\CustomViewElement;
use ChriCo\Fields\View\Errors;
use ChriCo\Fields\View\Input;
use ChriCo\Fields\View\Label;
use ChriCo\Fields\View\Textarea;
use ChriCo\Fields\ViewFactory;

class ViewFactoryTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new ViewFactory();
        static::assertInstanceOf(ViewFactory::class, $testee);
    }

    /**
     * Test if creating a View-class via valid type.
     *
     * @param $type
     * @param $expected
     *
     * @dataProvider provide_create
     * @test
     */
    public function test_create($type, $expected): void
    {

        $testee = new ViewFactory();
        static::assertInstanceOf($expected, $testee->create($type));
    }

    public function provide_create(): \Generator
    {

        yield 'label type' => ['label', Label::class];
        yield 'errors type' => ['errors', Errors::class];
        yield 'text type' => ['text', Input::class];
        yield 'class name' => [Textarea::class, Textarea::class];
    }

    /**
     * Test if we can create an instance of a custom view-element.
     * @test
     */
    public function test_create__custom_view_element(): void
    {

        $expected = CustomViewElement::class;
        static::assertInstanceOf($expected, (new ViewFactory())->create($expected));
    }

    /**
     * Test if an unknown type throws an Exception.
     * @test
     */
    public function test_create__unknown_type(): void
    {
        static::expectException(UnknownTypeException::class);
        $testee = new ViewFactory();
        $testee->create('i am an unknown type');
    }
}
