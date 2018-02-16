<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\Collection;
use ChriCo\Fields\View\RenderableElementInterface;

class CollectionTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Collection();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\InvalidClassException
     */
    public function test_render__invalid_element()
    {

        /** @var \Mockery\MockInterface|ElementInterface $stub */
        $stub = \Mockery::mock(ElementInterface::class);
        $stub->shouldReceive('get_name')
            ->andReturn('');

        (new Collection())->render($stub);
    }
}