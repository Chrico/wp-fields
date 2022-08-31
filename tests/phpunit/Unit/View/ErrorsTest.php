<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Errors;
use ChriCo\Fields\View\RenderableElementInterface;

class ErrorsTest extends AbstractViewTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new Errors();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     *
     * @param array  $input
     * @param string $expected
     *
     * @dataProvider provide_render
     * @test
     */
    public function test_render(array $input, string $expected): void
    {

        $element = new Element('name');
        $element->withErrors($input);

        static::assertSame(
            $expected,
            (new Errors())->render($element)
        );
    }

    public function provide_render(): \Generator
    {

        yield 'no errors' => [
            [],
            ''
        ];

        yield '1 error' => [
            ['foo' => 'bar'],
            sprintf(
                Errors::WRAPPER_MARKUP,
                sprintf(Errors::ERROR_MARKUP, 'bar')
            )
        ];
    }

    /**
     * Test if we can change the markup around the error output.
     * @test
     */
    public function test_render__changed_markup(): void
    {

        $element = new Element('name');
        $element->withErrors(['foo' => 'bar']);

        $markup = [
            'wrapper' => '<ul>%s</ul>',
            'error'   => '<li>%s</li>'
        ];

        static::assertSame(
            '<ul><li>bar</li></ul>',
            (new Errors($markup))->render($element)
        );
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

        (new Errors())->render($stub);
    }
}
