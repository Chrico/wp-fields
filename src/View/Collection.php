<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\ViewFactory;

/**
 * Class Collection
 *
 * @package ChriCo\Fields\View
 */
class Collection implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    protected ViewFactory $factory;

    /**
     * Collection constructor.
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
     * @param ElementInterface|Element\CollectionElement $element
     *
     * @return string
     * @throws InvalidClassException
     *
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, Element\CollectionElement::class);

        $row = $this->factory->create(FormRow::class);

        $html = array_reduce(
            $element->elements(),
            function ($html, ElementInterface $next) use ($element, $row): string {
                // In case we have nested CollectionElement, then
                // we don't want to nest those when rendering.
                if ($next instanceof Element\CollectionElement) {
                    $html .= $this->render($next);
                } else {
                    $html .= $row->render($next);
                }

                return $html;
            },
            ''
        );

        $attributes = $element->attributesForView();
        $attributes = $this->buildCssClasses($attributes, 'collection', $element);
        // we do not want to get the "name" and "type" rendered as attribute on wrapper.
        unset($attributes['name'], $attributes['type']);

        $errors = $this->factory->create(Errors::class)
            ->render($element);
        if ($errors !== '') {
            $attributes['class'] .= ' form-collection--has-errors';
        }

        return sprintf(
            '%1$s<div %2$s>%3$s</div>',
            $errors,
            $this->attributesToString($attributes),
            $html
        );
    }
}
