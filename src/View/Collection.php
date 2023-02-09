<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element;
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
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        if (! $element instanceof Element\CollectionElement) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" has to implement "%s"',
                    $element->name(),
                    Element\CollectionElement::class
                )
            );
        }

        $row = $this->factory->create(FormRow::class);

        $html = array_reduce(
            $element->elements(),
            function ($html, ElementInterface $next) use ($element, $row): string {
                // Adding the CollectionElement name to the Element name and ID as prefix.
                $next->withAttribute('id', $element->id().'_'.$next->id());
                $next->withAttribute('name', $element->name().'['.$next->name().']');

                // In case we have nested CollectionElement, then
                // we don't want to nest "<table><tr>"-wrapper.
                if ($next instanceof Element\CollectionElement) {
                   $html .= $this->render($next);
                } else {
                    $html .= $row->render($next);
                }

                return $html;
            },
            ''
        );

        $errors = $this->factory->create(Errors::class)
            ->render($element);
        $class = $errors !== ''
            ? 'form-table--has-errors'
            : '';

        return sprintf('%1$s<table class="form-table %2$s">%3$s</table>', $errors, $class, $html);
    }
}
