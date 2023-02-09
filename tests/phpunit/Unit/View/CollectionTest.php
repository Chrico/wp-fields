<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\Element;
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

    /**
     * @test
     */
    public function testRenderNestedCollectionElements(): void
    {
        $expectedName1 = 'name1';
        $expectedName2 = 'name2';
        $expectedTextElementName = 'text';

        $textElement = new Element($expectedTextElementName);
        $textElement->withAttribute('type', 'text');

        $childCollection = new CollectionElement($expectedName2);
        $childCollection->withElement($textElement);

        $rootCollection = new CollectionElement($expectedName1);
        $rootCollection->withElement($childCollection);

        $result = (new Collection())->render($rootCollection);

        static::assertNotEmpty($result);
    }
}
