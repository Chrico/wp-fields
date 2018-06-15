<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\View\Form;
use ChriCo\Fields\View\RenderableElementInterface;

class FormTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {

        $testee = new Form();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\InvalidClassException
     */
    public function test_render__invalid_element()
    {

        /** @var \Mockery\MockInterface|ElementInterface $stub */
        $stub = \Mockery::mock(ElementInterface::class);
        $stub->shouldReceive('name')
            ->andReturn('');

        (new Form())->render($stub);
    }

    /**
     * Test if a single element is rendered.
     */
    public function test_render()
    {

        $expected_name = 'foo';

        $element_stub = \Mockery::mock(ElementInterface::class);
        $element_stub->shouldReceive('name')
            ->andReturn($expected_name);
        $element_stub->shouldReceive('type')
            ->andReturn('text');
        $element_stub->shouldReceive('attributes')
            ->andReturn([]);

        $form_stub = \Mockery::mock(FormInterface::class, ElementInterface::class);
        $form_stub->shouldReceive('name')
            ->andReturn('form');
        $form_stub->shouldReceive('attributes')
            ->andReturn([]);
        $form_stub->shouldReceive('elements')
            ->andReturn([$element_stub]);

        $output = (new Form())->render($form_stub);

        static::assertContains('<form', $output);
        static::assertContains('</form>', $output);
    }

}