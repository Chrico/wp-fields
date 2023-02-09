<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\View\Errors;
use ChriCo\Fields\View\RenderableElementInterface;
use Generator;
use Mockery;
use Mockery\MockInterface;

class ErrorsTest extends AbstractViewTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
    {
        $testee = new Errors();
        static::assertInstanceOf(RenderableElementInterface::class, $testee);
    }

    /**
     * Test rendering of an Element.
     *
     * @param array $input
     * @param string $expected
     *
     * @dataProvider provideRender
     * @test
     */
    public function testRender(array $input, string $expected): void
    {
        $element = new Element('name');
        $element->withErrors($input);

        static::assertSame(
            $expected,
            (new Errors())->render($element)
        );
    }

    public function provideRender(): Generator
    {
        yield 'no errors' => [
            [],
            '',
        ];

        yield '1 error' => [
            ['foo' => 'bar'],
            sprintf(
                Errors::WRAPPER_MARKUP,
                sprintf(Errors::ERROR_MARKUP, 'bar')
            ),
        ];
    }

    /**
     * Test if we can change the markup around the error output.
     *
     * @test
     */
    public function testRenderChangedMarkup(): void
    {
        $element = new Element('name');
        $element->withErrors(['foo' => 'bar']);

        $markup = [
            'wrapper' => '<ul>%s</ul>',
            'error' => '<li>%s</li>',
        ];

        static::assertSame(
            '<ul><li>bar</li></ul>',
            (new Errors($markup))->render($element)
        );
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

        (new Errors())->render($stub);
    }
}
