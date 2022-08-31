<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Collection;
use ChriCo\Fields\View\RenderableElementInterface;

class CollectionTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Collection();
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

        (new Collection())->render($stub);
    }
}
