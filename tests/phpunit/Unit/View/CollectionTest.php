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
        $rootCollectionName = 'rootCollection';
        $childCollectionName = 'childCollection';
        $textElementName = 'text';
        $textElementWithLabelName = 'text-with-label';

        $textElement = new Element($textElementName);
        $textElement->withAttribute('type', 'text');

        $textElementWithLabel = new Element($textElementWithLabelName);
        $textElementWithLabel->withAttribute('type', 'text');
        $textElementWithLabel->withLabel('Some Label');

        $childCollection = new CollectionElement($childCollectionName);
        $childCollection->withElement($textElement);
        $childCollection->withElement($textElementWithLabel);

        $rootCollection = new CollectionElement($rootCollectionName);
        $rootCollection->withElement($childCollection);

        $result = (new Collection())->render($rootCollection);

        static::assertNotEmpty($result);
    }
}
