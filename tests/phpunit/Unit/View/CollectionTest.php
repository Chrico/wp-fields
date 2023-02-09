<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Collection;
use ChriCo\Fields\View\RenderableElementInterface;
use Mockery;
use Mockery\MockInterface;

class CollectionTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Collection();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @test
     */
    public function testRenderInvalidElement(): void
    {
        static::expectException(InvalidClassException::class);
        /** @var MockInterface|ElementInterface $stub */
        $stub = Mockery::mock(ElementInterface::class);
        $stub->allows('name')
            ->andReturn('');

        (new Collection())->render($stub);
    }
}
