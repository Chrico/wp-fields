<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Form;
use ChriCo\Fields\View\RenderableElementInterface;

class FormTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Form();
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

        (new Form())->render($stub);
    }

    /**
     * Test if a single element is rendered.
     * @test
     */
    public function test_render(): void
    {

        $expected_name = 'foo';

        $element_stub = \Mockery::mock(ElementInterface::class);
        $element_stub->allows('name')
            ->andReturn($expected_name);
        $element_stub->allows('type')
            ->andReturn('text');
        $element_stub->allows('attributes')
            ->andReturn([]);

        $form_stub = \Mockery::mock(FormInterface::class, ElementInterface::class, CollectionElementInterface::class);
        $form_stub->allows('name')
            ->andReturn('form');
        $form_stub->allows('attributes')
            ->andReturn([]);
        $form_stub->allows('elements')
            ->andReturn([$element_stub]);

        $output = (new Form())->render($form_stub);

        static::assertStringContainsString('<form', $output);
        static::assertStringContainsString('</form>', $output);
    }
}
