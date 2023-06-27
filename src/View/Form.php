<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Exception\UnknownTypeException;
use ChriCo\Fields\ViewFactory;

/**
 * Class Form
 *
 * @package ChriCo\Fields\View
 */
class Form implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    protected ViewFactory $factory;

    /**
     * Form constructor.
     *
     * @param ViewFactory|NULL $factory
     */
    public function __construct(ViewFactory $factory = null)
    {
        $this->factory = $factory === null
            ? new ViewFactory()
            : $factory;
    }

    /**
     * @param ElementInterface|FormInterface $element
     *
     * @return string
     * @throws InvalidClassException|UnknownTypeException
     *
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, FormInterface::class);

        $row = $this->factory->create(FormRow::class);

        $html = array_reduce(
            $element->elements(),
            static function ($html, ElementInterface $next) use ($element, $row): string {
                $html .= $row->render($next);

                return $html;
            },
            ''
        );

        $attributes = $element->attributes();
        // Don't re-use the "type" as attribute on <form>-tag.
        unset($attributes['type']);
        $classes = (string) ($attributes['class'] ?? '');
        $classes .= 'form';
        $attributes['class'] = $classes;

        $errors = $element instanceof ErrorAwareInterface
            ? $this->factory->create(Errors::class)
                ->render($element)
            : '';
        if ($errors !== '') {
            $attributes['class'] .= ' form--has-errors';
        }

        return sprintf(
            '<form %1$s>%2$s %3$s</form>',
            $this->attributesToString($attributes),
            $errors,
            $html
        );
    }
}
