<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Exception\ElementNotFoundException;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

class CollectionElementTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function testBasic(): void
    {
        $expected_name = 'name';
        $testee = new CollectionElement($expected_name);

        static::assertInstanceOf(Element::class, $testee);
        static::assertInstanceOf(ElementInterface::class, $testee);

        static::assertEmpty($testee->elements());
    }

    /**
     * Basic test to add 1 element and get the element back.
     *
     * @test
     */
    public function testAddGetElements(): void
    {
        $element_name = 'element-name';

        $element = new Element($element_name);

        $testee = new CollectionElement('collection-name');
        $testee->withElement($element);

        $elements = $testee->elements();

        static::assertCount(1, $elements);
        static::assertArrayHasKey($element_name, $elements);
        static::assertSame($element, $elements[$element_name]);
        static::assertSame($element, $testee->element($element_name));
    }

    /**
     * @test
     */
    public function testGetElementNotExistent(): void
    {
        static::expectException(ElementNotFoundException::class);
        (new CollectionElement('collection'))->element('not existing element');
    }

    /**
     * Test if success/failure is returned for not existing/existing elements.
     *
     * @test
     */
    public function testHasElement(): void
    {
        $expected_element_name = 'element';
        $testee = new CollectionElement('collection');

        static::assertFalse($testee->elementExists($expected_element_name));

        $testee->withElement(new Element($expected_element_name));
        static::assertTrue($testee->elementExists($expected_element_name));
    }

    /**
     * Test if errors which are not matching with the element name are assigned to the collection itself.
     *
     * @test
     */
    public function testAddErrors(): void
    {
        $expected_error = ['error_message'];

        $testee = new CollectionElement('collection');
        $testee->withElement(new Element('element'));
        $testee->withErrors($expected_error);

        static::assertSame($expected_error, $testee->errors());
    }

    /**
     * Test if errors are delegated to the elements in the collection.
     *
     * @test
     */
    public function testAddErrorsDeletaged(): void
    {
        $expected_element_name = 'element';
        $expected_error = [$expected_element_name => 'error_message'];

        $testee = new CollectionElement('collection');
        $testee->withElement(new Element($expected_element_name));
        $testee->withErrors($expected_error);

        /** @var ErrorAwareInterface $element */
        $element = $testee->element($expected_element_name);

        static::assertCount(1, $element->errors());
        static::assertSame(array_values($expected_error), array_values($element->errors()));
    }

    /**
     * @test
     */

    public function testChildHasError(): void
    {
        $errorStub = \Mockery::mock(\WP_Error::class);
        $errorStub->expects('get_error_messages')->andReturn(['some' => 'error message']);

        \Brain\Monkey\Functions\expect('is_wp_error')->andReturn(true);

        $child = new Element('element');
        $child->withValidator(static fn() => $errorStub);

        $testee = new CollectionElement('collection');
        $testee->withElement($child);

        static::assertFalse($testee->validate());
        static::assertTrue($testee->hasErrors());
    }

    /**
     * Test if values are delegated to the elements in the collection.
     *
     * @test
     */
    public function testSetAttributeValue(): void
    {
        $expected_element_name = 'element';
        $expected_value = 'the value';

        $testee = new CollectionElement('collection');
        $testee->withElement(new Element($expected_element_name));
        $testee->withValue([$expected_element_name => $expected_value]);

        $element = $testee->element($expected_element_name);

        static::assertSame($expected_value, $element->value());
    }

    /**
     * Test if we can get all values back again after delegation on set_value().
     *
     * @test
     */
    public function testGetAttributeValue(): void
    {
        $expected_element_name = 'element';
        $expected_value = 'the value';
        $expected = [$expected_element_name => $expected_value];

        $testee = new CollectionElement('collection');
        $testee->withElement(new Element($expected_element_name));
        $testee->withValue($expected);

        static::assertSame($expected, $testee->value());
    }
}
